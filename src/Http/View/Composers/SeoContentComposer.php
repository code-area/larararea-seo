<?php

namespace LaraAreaSeo\Http\View\Composers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\View;
use LaraAreaSeo\Cache\CachedSeo;
use LaraAreaSeo\Models\SeoConfig;
use LaraAreaSeo\Traits\MetaTagTrait;

class SeoContentComposer
{
    use MetaTagTrait;

    public function compose(View $view)
    {
        $seoContent = $this->getSeoContent($view->getData());
        $view->with('seoContent', $seoContent);
    }

    protected function getSeoContent($resources)
    {
        $currentRoute = Route::current();

        if(empty($currentRoute)) {
            return $this->getDefaultContent($resources);
        }

        $name = $currentRoute->getName();
        $uri = $currentRoute->uri();
        $uriPrefix = config('laraarea_seo.uri.prefix');
        if ($uriPrefix) {
            $uri = Str::replaceFirst($uriPrefix, '', $uri);
        }

        foreach ($currentRoute->parameters as $parameter => $value) {
            $uri = str_replace('{' . $parameter .'}', $value, $uri);
        }

        $seoList = CachedSeo::seoList();
        $name = $name ?? SeoConfig::ROUTE_NAME;

        $routeSeoList = $seoList[$name] ?? $seoList[SeoConfig::ROUTE_NAME] ?? [];
        $seoData = $routeSeoList[$uri] ?? $routeSeoList[SeoConfig::URI] ?? [];
        if (empty($seoData)) {
            return $this->getDefaultContent($resources);
        }
        return $this->getMetaContent($seoData['data'], $seoData['is_minify'], $seoData['tags'], $resources, $seoData['template']);
    }

    protected function getDefaultContent($resources)
    {
        $defaultSeo = CachedSeo::defaultSeo();
        if ($defaultSeo && $defaultSeo->is_active) {
            return $this->getMetaContent($defaultSeo->meta_json, $defaultSeo->is_minify, $defaultSeo->tags, $resources, $defaultSeo->template);
        }

        return '';
    }
}
