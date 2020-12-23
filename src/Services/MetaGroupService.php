<?php

namespace LaraAreaSeo\Services;

use LaraAreaSeo\Cache\CachedSeo;

class MetaGroupService extends BaseService
{
    /**
     * @param $item
     */
    protected function itemSaved($item)
    {
        CachedSeo::updateMetaGroups();
        CachedSeo::updateMetas();
    }

    /**
     * @param $item
     * @return mixed
     */
    protected function itemDeleted($item)
    {
        CachedSeo::updateMetaGroups();
        CachedSeo::updateMetas();
    }
}
