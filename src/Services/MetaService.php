<?php

namespace LaraAreaSeo\Services;

use Illuminate\Support\Arr;
use LaraAreaSeo\Cache\CachedSeo;

class MetaService extends BaseService
{
    /**
     * @param $item
     * @param $with
     */
    public function createRelations($item, $with)
    {
        $this->saveMetaGroups($item, $with);
    }

    /**
     * @param $item
     * @param $with
     */
    public function updateRelations($item, $with)
    {
        $this->saveMetaGroups($item, $with);
    }

    /**
     * @param $item
     * @param $with
     */
    public function saveMetaGroups($item, $with)
    {
        $values = Arr::get($with, 'meta_groups', []);
        $sync = [];
        foreach ($values as $groupId => $value) {
            if ($value['meta_group_id'] == 1) {
                $sync[$groupId] = [
                    'default_content' => $value['default_content']
                ];
            }
        }
        $item->meta_groups()->sync($sync);
    }

    /**
     * @param $item
     */
    protected function itemSaved($item)
    {
        CachedSeo::updateMetas();
    }

    /**
     * @param $item
     * @return mixed
     */
    protected function itemDeleted($item)
    {
        CachedSeo::updateMetas();
    }
}
