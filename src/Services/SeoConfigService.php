<?php

namespace LaraAreaSeo\Services;

use LaraAreaSeo\Cache\CachedSeo;
use LaraAreaSeo\Models\SeoConfig;
use Illuminate\Support\Facades\App;

class SeoConfigService extends BaseService
{
    /**
     * @param array $columns
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|mixed[]|object|null
     */
    public function findSingle($columns = ['*'], $with = [])
    {
        return $this->modelQuery
            ->when($with, function ($q) use ($with){
                $q->with($with);
            })
            ->first($columns);
    }

    /**
     * @param $data
     * @return \App\Models\BaseModel|\App\Models\BaseModel[]|bool|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|mixed|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function updateSingle($data)
    {
        $seoConfig = CachedSeo::seoConfig();
        $defaultSeo = CachedSeo::defaultSeo();

        if ($seoConfig) {
            $method = method_exists($this->validator, 'update') ? 'update' : 'create';
        } else {
            $method = 'create';
        }

        if (! $this->validate($data, $method)) {
            return false;
        }

        $seoConfig = $seoConfig ? $this->_update($seoConfig, $data) : $this->_create($data);
        CachedSeo::updateSeoConfig();

        $seoService = App::make(SeoService::class);
        $data['headline'] = SeoConfig::HEADLINE;
        $data['route_name'] = SeoConfig::ROUTE_NAME;
        $data['uri'] = SeoConfig::URI;
        $defaultSeo = $defaultSeo ? $seoService->_update($defaultSeo, $data) : $seoService->_create($data);
        CachedSeo::updateDefaultSeo();

        return [$seoConfig, $defaultSeo];
    }
}
