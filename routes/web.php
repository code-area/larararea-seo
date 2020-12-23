<?php
Route::group(config('laraarea_seo.web.route'), function () {
    Route::resource('seo', 'LaraAreaSeo\Http\Controllers\SeoController');
    Route::get('seo-configs', 'LaraAreaSeo\Http\Controllers\SeoConfigController@showSingle')->name('seo-configs.show');
    Route::put('seo-configs', 'LaraAreaSeo\Http\Controllers\SeoConfigController@updateSingle')->name('seo-configs.edit');
    Route::resource('seo-uris', 'LaraAreaSeo\Http\Controllers\SeoUriController');
    Route::resource('seo-routes', 'LaraAreaSeo\Http\Controllers\SeoRouteController');
    Route::get('seo-routes/{id}/tags', 'LaraAreaSeo\Http\Controllers\SeoRouteController@getDynamicTags');
    Route::resource('seo-meta-contents', 'LaraAreaSeo\Http\Controllers\SeoMetaContentController');
    Route::resource('metas', 'LaraAreaSeo\Http\Controllers\MetaController');
    Route::resource('meta-groups', 'LaraAreaSeo\Http\Controllers\MetaGroupController');

    $actions = [
        'index',
        'create',
        'store',
        'show',
        'edit',
        'update',
        'destroy',
    ];
    $translationAbleConfig = [
        'seo' => $actions,
        'seo-meta-contents' => $actions,
    ];
    foreach ($translationAbleConfig as $resource => $options) {
        $_resource = str_replace('-', '_', $resource);
        $class = \Illuminate\Support\Str::title($resource);
        $class = \Illuminate\Support\Str::singular($class);
        $class = str_replace('-', '', $class);
        if (in_array('index', $options)) {
            Route::get($resource . '/{translatable_id}/translations', 'LaraAreaSeo\Http\Controllers\\' . $class . 'Controller@translations')
                ->name($resource . '.translations.index');
        }
        if (in_array('create', $options)) {
            Route::get($resource . '/{translatable_id}/translations/create', 'LaraAreaSeo\Http\Controllers\\' . $class . 'Controller@createTranslation')
                ->name($resource . '.translations.create');
        }
        if (in_array('store', $options)) {
            Route::post($resource . '/{translatable_id}/translations/store', 'LaraAreaSeo\Http\Controllers\\' . $class . 'Controller@storeTranslation')
                ->name($resource . '.translations.store');
        }
        $transId = $_resource . '_translated_id';
        if (strlen($transId) > 32) {
            $transId = $_resource . '_tId';
        }

        if (in_array('show', $options)) {
            Route::get($resource . '/{translatable_id}/translations/{' . $transId . '}', 'LaraAreaSeo\Http\Controllers\\' . $class . 'Controller@showTranslation')
                ->name($resource . '.translations.show');
        }
        if (in_array('edit', $options)) {
            Route::get($resource . '/{translatable_id}/translations/{' . $transId . '}/edit', 'LaraAreaSeo\Http\Controllers\\' . $class . 'Controller@editTranslation')
                ->name($resource . '.translations.edit');
        }
        if (in_array('update', $options)) {
            Route::put($resource . '/{translatable_id}/translations/{' . $transId . '}', 'LaraAreaSeo\Http\Controllers\\' . $class . 'Controller@updateTranslation')
                ->name($resource . '.translations.update');
        }
        if (in_array('destroy', $options)) {
            Route::delete($resource . '/{translatable_id}/translations/{' . $transId . '}', 'LaraAreaSeo\Http\Controllers\\' . $class . 'Controller@destroyTranslation')
                ->name($resource . '.translations.destroy');
        }
    }
});
