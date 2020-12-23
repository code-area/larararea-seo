<?php

namespace LaraAreaSeo\Models;

/**
 * Class SeoConfig
 * @package LaraAreaSeo\Models
 */
class SeoConfig extends TranslateAbleModel
{
    const HOME_ROUTE  = 'home';
    const HEADLINE  = '_default_';
    const ROUTE_NAME  = '*';
    const URI  = '*';

    protected $descriptiveAttribute = 'headline';

    protected $table = 'seo_configs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title_required',
        'title_min',
        'title_max',
        'robots_required',
        'robots_min',
        'robots_max',
        'description_required',
        'description_min',
        'description_max',
        'keywords_required',
        'keywords_min',
        'keywords_max',
        'is_prepend_title',
        'is_append_title',
        'title_prepend_separator',
        'title_append_separator',
    ];

    /**
     * @var array
     */
    protected $translateable = [
        'is_prepend_title',
        'is_append_title',
        'title_prepend_separator',
        'title_append_separator',
    ];
}
