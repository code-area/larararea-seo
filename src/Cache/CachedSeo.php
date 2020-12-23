<?php

namespace LaraAreaSeo\Cache;

use LaraAreaSeo\Models\Meta;
use LaraAreaSeo\Models\MetaGroup;
use LaraAreaSeo\Models\Seo;
use LaraAreaSeo\Models\SeoConfig;
use LaraAreaSeo\Models\SeoRoute;
use LaraAreaCacheManager\CacheManager;

/**
 * Class CachedSeo
 * @package App\Helpers
 * @method static \Illuminate\Database\Eloquent\Collection seoList()
 * @method static \Illuminate\Database\Eloquent\Collection updateSeoList()
 * @method static \Illuminate\Database\Eloquent\Collection metas()
 * @method static \Illuminate\Database\Eloquent\Collection updateMetas()
 * @method static \Illuminate\Database\Eloquent\Collection metaGroups()
 * @method static \Illuminate\Database\Eloquent\Collection updateMetaGroups()
 * @method static \Illuminate\Database\Eloquent\Collection seoConfig()
 * @method static \Illuminate\Database\Eloquent\Collection updateSeoConfig()
 * @method static \Illuminate\Database\Eloquent\Collection defaultSeo()
 * @method static \Illuminate\Database\Eloquent\Collection updateDefaultSeo()
 * @method static \Illuminate\Database\Eloquent\Collection seoRoutes()
 * @method static \Illuminate\Database\Eloquent\Collection updateSeoRoutes()
 *
 */
class CachedSeo extends CacheManager
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function metasData()
    {
        return Meta::with('meta_groups')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function metaGroupsData()
    {
        return MetaGroup::get();
    }

    /**
     * @return SeoConfig|\Illuminate\Database\Eloquent\Model|object|null
     */
    public static function seoConfigData()
    {
        return SeoConfig::first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public static function defaultSeoData()
    {
        return Seo::where(['headline' => SeoConfig::HEADLINE])->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public static function seoRoutesData()
    {
        return SeoRoute::with('seo_uris')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function seoListData()
    {
        $model = new Seo();
        $lang = 'en';
//        $translationsData['columns'] = $model->getTranslateAbleColumns();
//        $translationsData['columns'][] = 'parent_id';
//        $translationsData['where'] = ['lang' => $lang];
//
        $seoList = \LaraAreaSeo\Models\Seo::where('is_active', \ConstYesNo::YES)
//            ->with(['translations' => function ($query) use ($lang) {
//                $query->select(['id', 'parent_id', 'tags'])
//                    ->where(['lang' => $lang]);
//            }])
            ->whereNull('parent_id')
            ->whereNotNull('route_name')
            ->with('seo_route:name,uri,template')->get();

        $seoData = [];
        foreach ($seoList->groupBy('route_name') as $routeName => $seoGroup) {
            $data = [];
            $isSingle = 1 == $seoGroup->count();
            foreach ($seoGroup as $seo) {
                if ($isSingle) {
                    $uri = '*';
                } else {
                    $uri = $seo->seo_route->uri == $seo->uri ? '*' : ($seo->uri != '/' ? $seo->uri : '');
                }
                $data[$uri] = [
                    'data' => $seo->meta_json ?? [],
                    'tags' => array_values($seo->tags ?? []),
                    'template' => $seo->seo_route->template,
                ];
            }
            $seoData[$seo->route_name] = $data;
        }

        return $seoData;
    }
}
