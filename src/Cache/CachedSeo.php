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
        return \LaraAreaSeo\Models\Seo::where('is_active', \ConstYesNo::YES)
            ->with(['translations' => function ($query)  {
                $query->where('is_active', \ConstYesNo::YES);
            }])
            ->whereNull('parent_id')
            ->whereNotNull('route_name')
            ->with('seo_route:name,uri,template')->get();
    }
}
