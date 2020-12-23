<?php

namespace LaraAreaSeo\Http\Controllers;

use Illuminate\Http\Request;
use LaraAreaSeo\Cache\CachedSeo;
use LaraAreaSeo\Services\SeoConfigService;
use LaraAreaSeo\Traits\TranslationControllerTrait;

class SeoConfigController extends BaseController
{
    use TranslationControllerTrait;

    /**
     * @var SeoConfigService
     */
    protected $service;

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSingle()
    {
        $seoConfig = CachedSeo::seoConfig();
        $item = CachedSeo::defaultSeo();

        $formPartials = $this->getViewPaths('partials.form');

        if ($item) {
            $paths = $this->getViewPaths('edit');
        } else {
            $paths = $this->getViewPaths('create');
        }
        return view()->first($paths, ['seoConfig' => $seoConfig, 'item' => $item, 'resource' => $this->resource, 'layout' => $this->layout, 'formPartials' => $formPartials]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function updateSingle(Request $request)
    {
        $isSaved = $this->service->updateSingle($request->all());
        return $isSaved ? $this->showSingle() : $this->redirectBack();
    }
}
