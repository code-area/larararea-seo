<?php

namespace LaraAreaSeo\Http\View\Composers;

use Illuminate\View\View;
use LaraAreaSeo\Cache\CachedSeo;

class SeoComposer
{
    public function compose(View $view)
    {
        $seoRoutes = CachedSeo::seoRoutes();
        if ($view->item) {
            $seoRoute = CachedSeo::findInCache('seoRoutes', $view->item->route_name, 'name');
            if ($seoRoute) {
                $seoUris = $seoRoute->seo_uris->pluck('uri', 'uri')->all();
                $seoUris = array_merge([$seoRoute->uri => $seoRoute->uri], $seoUris);
            } else {
                $seoUris = [];
            }
        } else {
            $seoUris = [];
        }

        $metas = CachedSeo::metas()->where('is_active', \ConstYesNo::YES);
        $metaGroups = [];

        $mainGroup = new \stdClass();
        $mainGroup->starts_with = '';
        $mainGroup->id = null;
        $mainGroup->headline = '';

        foreach ($metas as $meta) {
            if (empty($meta->only_in_groups)) {
                $metaGroups[$mainGroup->headline]['group'] = $mainGroup;
                $metaGroups[$mainGroup->headline]['metas'][] = $meta;
            }

            foreach ($meta->meta_groups as $metaGroup) {
                if ($metaGroup->is_active) {
                    $metaGroups[$metaGroup->headline]['group'] = $metaGroup;
                    $metaGroups[$metaGroup->headline]['metas'][] = $meta;
                }
            }
        }
        $metas = collect($metaGroups)->sortKeys();

        $itemSeoMetaContents = $view->item ?
            $view->item->seo_meta_contents()->get(['seo_meta_contents.id', 'seo_meta_contents.meta_id', 'seo_meta_contents.meta_group_id', 'seo_meta_contents.is_active', 'seo_meta_contents.content'])
            : collect();

        $view->with(compact('seoUris', 'seoRoutes', 'metas', 'itemSeoMetaContents'));
    }

}
