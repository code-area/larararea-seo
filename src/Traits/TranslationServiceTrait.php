<?php

namespace LaraAreaSeo\Traits;

use Illuminate\Support\Arr;
use LaraAreaSeo\Models\BaseModel;
use LaraAreaTranslation\Traits\TranslationTrait;

trait TranslationServiceTrait
{
    /**
     * @var TranslationTrait;
     */
    protected $model;

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
     * @param $id
     * @param bool $isUnique
     * @return bool
     */
    public function canAddTranslations($id, $isUnique = true)
    {
        if (! $isUnique) {
            return true;
        }

        $languages = collect(config('iso_languages'))->where('is_active')->pluck('language', 'iso2')->all();
        $count = $this->model->where('parent_id', $id)->whereIn('lang', array_keys($languages))->count();
        return $count != count($languages);
    }

    /**
     * @param $parentId
     * @param string $group
     * @return mixed
     */
    public function paginateTranslations($parentId, $group = \ConstIndexableGroup::TRANSLATIONS)
    {
        $data = $this->model->getPaginateAble(true, $group);
        $columns = $data['columns'];
        array_unshift($columns, $this->model->getKeyName());
        $columns = array_unique($columns);
        $items = $this->model->select($columns)
            ->when($data['withCount'], function ($query) use ($data) {
                $query->withCount($data['withCount']);
            })->when($data['with'], function ($query) use ($data) {
                $query->with($data['with']);
            })->where('parent_id', $parentId)
            ->paginate();

        $this->addPropertiesToPaginator($items, $group);
        return $items;
    }

    /**
     * @param $parentId
     * @param $data
     * @return BaseModel|bool|\Illuminate\Database\Eloquent\Model
     */
    public function createTranslation($parentId, $data)
    {
        $data['parent_id'] = $parentId;
        $with = Arr::pull($data, 'with', []);

        $method = method_exists($this->validator, 'translation') ? 'translation' : 'create';
        if ($this->validate($data, $method)) {
            $data = $this->fixDataForCreate($data);
            $item =  $this->model->create($data);
            if (is_a($item, get_class($this->model))) {
                $this->createTranslationRelations($item, $with);
                $this->translationsCreated($item);
            }
            return $item;
        }

        return false;
    }

    /**
     * @param $parentId
     * @param $id
     * @param $data
     * @return BaseModel[]|bool|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function updateTranslation($parentId, $id, $data)
    {
        $data['id'] = $id;
        $data['parent_id'] = $parentId;
        $with = Arr::pull($data, 'with', []);
        $method = method_exists($this->validator, 'translation')
            ? 'translation'
            : ( method_exists($this->validator, 'update') ? '' : 'update');
        if ($this->validate($data, $method)) {
            $data = $this->fixDataForUpdateTranslation($data);
            $item = $this->model->find($id);
            if (empty($item)) {
                return false;
            }

            if ($item->update($data)) {
                $this->updateRelations($item, $with);
                $this->translationsUpdated($item);
            }
            return $item;
        }

        return false;
    }

    /**
     * @param $item
     * @param $with
     */
    public function createTranslationRelations($item, $with) {

    }

    /**
     * @param $data
     * @return mixed
     */
    public function fixDataForUpdateTranslation($data)
    {
        return $data;
    }

    /**
     * @param $id
     * @param $columns
     * @return mixed
     */
    public function findMain($id, $columns = null)
    {
        if (empty($columns)) {
            $columns = ['id', $this->model->getDescriptiveAttributeName()];
        }
        return $this->whereNull('parent_id')->find($id, $columns);
    }

    /**
     * @param $parentId
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function findTranslation($parentId, $id, $columns = ['*'])
    {
        return $this->model->where('parent_id', $parentId)->find($id, $columns);
    }

    /**
     * @param $parentId
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|null
     */
    public function deleteTranslation($parentId, $id)
    {
        $model = $this->model->where('parent_id', $parentId)->find($id, [$this->model->getKeyName()]);
        if ($model) {
            return $this->deleteExistingTranslation($model);
        }

        return $model;
    }

    /**
     * @param $model
     * @return mixed
     */
    public function deleteExistingTranslation($model)
    {
        $deleted = $model->delete();
        if ($deleted) {
            $this->translationsDeleted($model);
        }

        return $deleted;
    }


    /**
     * @param $item
     * @return mixed
     */
    protected function translationsCreated($item)
    {
        return $item;
    }

    /**
     * @param $item
     * @return mixed
     */
    protected function translationsUpdated($item)
    {
        return $item;
    }

    /**
     * @param $item
     * @return mixed
     */
    protected function translationsDeleted($item)
    {
        return $item;
    }

    /**
     * @return array
     */
    public function getTranslatableColumns()
    {
        return array_merge(['id'] , $this->model->getTranslateAbleColumns());
    }
}
