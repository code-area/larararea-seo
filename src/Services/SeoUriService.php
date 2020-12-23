<?php

namespace LaraAreaSeo\Services;

use App\Services\Admin\AdminService;
use LaraAreaSeo\Cache\CachedSeo;

class SeoUriService extends AdminService
{
    /**
     * @param $item
     */
    protected function itemSaved($item)
    {
        CachedSeo::updateSeoRoutes();
    }

    /**
     * @param $item
     * @return mixed|void
     */
    protected function itemDeleted($item)
    {
        CachedSeo::updateSeoRoutes();
    }
}
