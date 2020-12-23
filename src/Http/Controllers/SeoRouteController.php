<?php

namespace LaraAreaSeo\Http\Controllers;

use LaraAreaSeo\Cache\CachedSeo;

class SeoRouteController extends BaseController
{
    public function getDynamicTags($routeName)
    {
        $seoRoute = CachedSeo::findInCache('seoRoutes', $routeName, 'name');
        $html = '';
        $tags = $seoRoute->all_tags ?? [];
        $tags = array_merge(['url'], $tags);
        $tags = array_unique($tags);

        foreach ($tags as $tag) {
            $html .= sprintf('<div class="col-sm-2 copyable">%s</div>', str_replace('%', $tag, $seoRoute->template));
        }

        return $html;
    }
}
