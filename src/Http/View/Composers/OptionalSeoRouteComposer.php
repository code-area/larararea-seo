<?php

namespace LaraAreaSeo\Http\View\Composers;

use Illuminate\Support\Str;
use Illuminate\View\View;
use LaraAreaSeo\Cache\CachedSeo;

class OptionalSeoRouteComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $seoRoutes = CachedSeo::seoRoutes();
        $optionalSeoRoutes = $seoRoutes->filter(function ($item) {
            return Str::contains($item->uri, ':');
        });

        $view->with('optionalSeoRoutes', $optionalSeoRoutes);
    }
}
