<?php

namespace LaraAreaSeo\Services;

use LaraAreaSeo\Cache\CachedSeo;
use LaraAreaSeo\Models\SeoConfig;
use LaraAreaSeo\Models\SeoRoute;
use LaraAreaSeo\Models\SeoUri;
use LaraAreaSeo\Traits\MetaTagTrait;
use Illuminate\Support\Arr;
use LaraAreaSeo\Traits\TranslationServiceTrait;

class SeoService extends BaseService
{
    use TranslationServiceTrait;
    use MetaTagTrait;

    /**
     * @param null $group
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($group = null)
    {
        $data = $this->model->getPaginateAble();
        $columns = $data['columns'];
        array_unshift($columns, $this->model->getKeyName());
        $columns = array_unique($columns);
        $search = $this->model->getPaginateAble(false, $group)->where('search', true);
        $searchValue = app('request')->get('search');

        $items = $this->model->select($columns)
            ->where('headline', '!=', SeoConfig::HEADLINE)
            ->when($data['withCount'], function ($query) use ($data) {
                $query->withCount($data['withCount']);
            })->when($data['with'], function ($query) use ($data) {
                $query->with($data['with']);
            })->whereNull('parent_id')
            ->when($searchValue && $search->isNotEmpty(), function ($q) use ($search, $searchValue) {
                $q->where(function ($q) use ($search, $searchValue) {
                    foreach ($search as $config) {
                        $q->orWhere($config['column'], 'like', '%' . $searchValue . '%');
                    }
                });
            })
            ->paginate();

        $this->addPropertiesToPaginator($items);
        return $items;
    }

    /**
     * @param $data
     * @param null $with
     * @return \App\Models\BaseModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function _create($data)
    {
        $seo =  parent::_create($data);
        $metaJson = $this->generateMetaJson($seo);
        $tags = $this->getTags($metaJson);
        $seo->update(['tags' => $tags, 'meta_json' => $metaJson]);
        CachedSeo::updateSeoList();

        return $seo;
    }

    /**
     * @param $item
     * @param $data
     * @param null $with
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function _update($item, $data)
    {
        $seo = parent::_update($item, $data);
        $metaJson = $this->generateMetaJson($seo);
        $tags = $this->getTags($metaJson);
        $seo->update(['tags' => $tags, 'meta_json' => $metaJson]);
        CachedSeo::updateSeoList();

        return $seo;
    }

	/**
     * @param $item
     * @return mixed
     */
    public function translationsCreated($seo)
    {
        $metaJson = $this->generateMetaJson($seo);
        $tags = $this->getTags($metaJson);
        $seo->update(['tags' => $tags, 'meta_json' => $metaJson]);

        CachedSeo::updateSeoList();
        return $seo;
    }

    /**
     * @param $item
     * @return mixed
     */
    protected function translationsUpdated($seo)
    {
        $metaJson = $this->generateMetaJson($seo);
        $tags = $this->getTags($metaJson);
        $seo->update(['tags' => $tags, 'meta_json' => $metaJson]);

        CachedSeo::updateSeoList();
        return $seo;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function fixDataForCreate($data)
    {
        if (key_exists('route_name', $data) && key_exists('uri', $data)) {
            $routeName = $data['route_name'];
            $routeUri = $data['uri'];
            if ($routeName && !SeoRoute::where('name', $routeName)->exists()) {
                SeoRoute::create([
                    'name' => $routeName
                ]);
            }

            if ($routeUri && !SeoUri::where('uri', $routeUri)->exists()) {
                SeoUri::create([
                    'uri' => $routeUri
                ]);
            }
        }

        return parent::fixDataForCreate($data);
    }

    /**
     * @param $item
     * @param $data
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function fixDataForUpdate($item, $data)
    {
        $data = $this->fixDataForCreate($data);
        return parent::fixDataForUpdate($item, $data);
    }

    /**
     * @param $item
     * @param $with
     */
    public function createRelations($item, $with)
    {
        $values = Arr::get($with, 'seo_meta_contents', []);
        $data = [];
        foreach ($values as $singleData) {
            if (! empty($singleData['content'])) {
                $data[] = $singleData;
            }
        }
        $savedRelations = $item->seo_meta_contents()->createMany($data);
        $item->setRelation('seo_meta_contents', $savedRelations);
    }

    /**
     * @param $item
     * @param $with
     */
    public function updateRelations($item, $with)
    {
        $values = Arr::get($with, 'seo_meta_contents', []);
        $oldSeoMetaContents = $item->seo_meta_contents()->get(['id', 'meta_id', 'meta_group_id']);
        $data = [];
        foreach ($values as $singleData) {
            $oldSeoMetaContent = $oldSeoMetaContents->where('meta_id', $singleData['meta_id'])
                ->where('meta_group_id', $singleData['meta_group_id'])
                ->first();

            if (! empty($singleData['content'])) {
                if ($oldSeoMetaContent) {
                    $oldSeoMetaContent->update($singleData);
                } else {
                    $data[] = $singleData;
                }
            } elseif ($oldSeoMetaContent) {
                $oldSeoMetaContent->delete();
            }
        }

        $newSeoMetaContents = $item->seo_meta_contents()->createMany($data);
        $metaContents = $oldSeoMetaContents->merge($newSeoMetaContents);
        $item->setRelation('seo_meta_contents', $metaContents);
    }

    public function createTranslationRelations($item, $with)
    {
        $this->createRelations($item, $with);
    }

    /**
     * @param $seo
     * @return array
     */
    public function generateMetaJson($seo)
    {
        $response = [];
        $title = $seo->title;

        if ($title) {
            $response['title'] = $title;
        }

        $metas = [];

        $keys = [
            'description',
            'keywords',
        ];

        foreach ($keys as $key) {
            if ($seo->{$key}) {
                $metas[] = [
                    'name' => $key,
                    'content' => $seo->{$key}
                ];
            }
        }

        if ($seo) {
            $metaContents = $seo->seo_meta_contents->where('is_active', 1);
            $metaContents->load('meta:id,attribute,attribute_value', 'meta_group:id,starts_with,headline,comment_start,comment_end');
            foreach ($metaContents->groupBy('meta_group_id') as $_metaContents) {
                $metaGroup = $_metaContents->first()->meta_group;

                if ($metaGroup && $metaGroup->comment_start) {
                    $metas[] = '<!--' . $metaGroup->comment_start . '-->';
                }

                foreach ($_metaContents as $metaContent) {
                    $meta = $metaContent->meta;
                    if ($metaContent->is_active && $meta) {
                        $metaAttributeValue = $metaGroup ? $metaGroup->starts_with .  $meta->attribute_value : $meta->attribute_value;
                        $metas[] = [
                            $meta->attribute => $metaAttributeValue,
                            'content' => $metaContent->content
                        ];
                    }
                }

                if ($metaGroup && $metaGroup->comment_end) {
                    $metas[] = '<!--' . $metaGroup->comment_end. '-->';
                }
            }
        }


        if ($seo->robots) {
            $metas[] = [
                'name' => 'robots',
                'content' => $seo->robots
            ];
        }
//        if ($next) {
//            $html[] = "<link rel=\"next\" href=\"{$next}\"/>";
//        }

        if ($seo->html) {
            $metas[] = $seo->html;
        }

//        return ($minify) ? implode('', $html) : implode(PHP_EOL, $html);

        $response['metas'] = $metas;
        return $response;
    }

    protected function generate($seo, $metaDefaults, $keys, $metas)
    {
        foreach ($keys as $key) {
            $value = $this->getValue($seo, $metaDefaults, $key);
            if ($value) {
                $metas[] = [
                    'name' => $key,
                    'content' => $value
                ];
            }
        }

        return $metas;
    }


    protected function getValue($seo, $defaults, $attribute)
    {
        return $seo->{$attribute} ?? $defaults[$attribute] ?? null;
    }


}
