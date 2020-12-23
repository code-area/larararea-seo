<?php

namespace LaraAreaSeo\Traits;

use LaraAreaSeo\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

trait TranslationControllerTrait
{
    /**
     * @var BaseService
     */
    protected $service;

    /**
     * @param $parentId
     * @return mixed
     */
    public function translations($parentId)
    {
        $main = $this->service->findMain($parentId);
        $items = collect();
        $addTranslations = false;

        if ($main) {
            $addTranslations = $this->service->canAddTranslations($parentId);
            $items = $this->service->paginateTranslations($parentId);
        }

        $paths = $this->getViewPaths('translations.index');
        return view()->first(
            $paths,
            [
                'items' => $items,
                'resource' => $this->resource,
                'main' => $main,
                'addTranslations' => $addTranslations,
                'layout' => $this->layout,
            ]
        );
    }

    /**
     * @param $parentId
     * @return mixed
     */
    public function createTranslation($parentId)
    {
        $columns = $this->service->getTranslatableColumns();
        $main = $this->service->findMain($parentId, $columns);
        $paths = $this->getViewPaths('translations.create');
        $formPartials = $this->getViewPaths('translations.partials.form');
        return view()->first(
            $paths,
            [
                'resource' => $this->resource,
                'main' => $main,
                'layout' => $this->layout,
                'formPartials' => $formPartials
            ]
        );
    }

    /**
     * @param Request $request
     * @param $parentId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function storeTranslation(Request $request, $parentId)
    {
        $isCreated = $this->service->createTranslation($parentId, $request->all());
        if ($isCreated) {
            flash('This ' . ucfirst($this->resource) . ' created successfully');
            $current = Route::currentRouteName();
            $current = str_replace('store', 'index', $current);
            return redirect()->route($current, Route::current()->parameters());
        }

        return $this->redirectBack();
    }


    /**
     * @param $translateId
     * @param $id
     * @return mixed
     */
    public function showTranslation($translateId, $id)
    {
        $item = $this->service->findTranslation($translateId, $id);
        $main = $this->service->findMain($translateId);
        $paths = $this->getViewPaths('translations.show');
        $showPartials = $this->getViewPaths('partials.show');
        return view()->first(
            $paths,
            [
                'item' => $item,
                'resource' => $this->resource,
                'main' => $main,
                'layout' => $this->layout,
                'showPartials' => $showPartials,
            ]
        );
    }

    /**
     * @param $translateId
     * @param $id
     * @return mixed
     */
    public function editTranslation($translateId, $id)
    {
        $item = $this->service->findTranslation($translateId, $id);
        $main = $this->service->findMain($translateId);
        $paths = $this->getViewPaths('translations.edit');
        $formPartials = $this->getViewPaths('translations.partials.form');
        return view()->first(
            $paths,
            [
                'item' => $item,
                'resource' => $this->resource,
                'main' => $main,
                'layout' => $this->layout,
                'formPartials' => $formPartials
            ]
        );
    }

    /**
     * @param Request $request
     * @param $translateId
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function updateTranslation(Request $request, $translateId, $id)
    {
        $isUpdated = $this->service->updateTranslation($translateId, $id, $request->all());
        if ($isUpdated) {
            $message = 'updated';
            return $this->redirectTo($message, 'index', 'update');
        }

        return $this->redirectBack();
    }

    /**
     * @param $translateId
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function destroyTranslation($translateId, $id)
    {
        $isDeleted = $this->service->deleteTranslation($translateId, $id);
        if ($isDeleted) {
            $message = 'deleted';
            return $this->redirectTo($message, 'index', 'destroy');
        }

        return $this->redirectBack();
    }
}
