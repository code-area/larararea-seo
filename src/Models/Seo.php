<?php

namespace LaraAreaSeo\Models;

use LaraAreaSeo\Traits\MetaTagTrait;

/**
 * Class Seo
 * @package LaraAreaSeo\Models
 */
class Seo extends TranslateAbleModel
{
    use MetaTagTrait;

    protected $descriptiveAttribute = 'headline';

    protected $table = 'seo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'is_active',
        'is_minify',
        'lang',
        'route_name',
        'headline',
        'uri',
        'title',
        'robots',
        'description',
        'keywords',
        'html',
        'tags',
        'meta_json',
    ];

    protected $casts = [
        'tags' => 'array',
        'meta_json' => 'array',
    ];


    /**
     * @var array
     */
    protected $paginateable = [
        'lang' => [
            'attribute' => 'language',
            'group' => \ConstIndexableGroup::TRANSLATIONS
        ],
        'headline',
        'route_name' => [
            'search' => true,
            'group' => [
                \ConstIndexableGroup::TRANSLATIONS,
                \ConstIndexableGroup::INDEX,
                null
            ]
        ],
        'uri' => [
            'search' => true,
            'group' => [
                \ConstIndexableGroup::TRANSLATIONS,
                \ConstIndexableGroup::INDEX,
                null
            ]
        ],
        'title' => [
            'group' => [
                \ConstIndexableGroup::TRANSLATIONS,
                \ConstIndexableGroup::INDEX,
            ]
        ],
        'description' => [
            'group' => [
                \ConstIndexableGroup::TRANSLATIONS,
                \ConstIndexableGroup::INDEX,
            ]
        ],
        'keywords' => [
            'group' => [
                \ConstIndexableGroup::TRANSLATIONS,
                \ConstIndexableGroup::INDEX,
            ]
        ],
    ];

    /**
     * @var array
     */
    protected $translateable = [
        'title',
        'robots',
        'description',
        'keywords',
        'html',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seo_meta_contents()
    {
        return $this->hasMany(SeoMetaContent::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seo_route()
    {
        return $this->belongsTo(SeoRoute::class, 'route_name', 'name');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seo_uri()
    {
        return $this->belongsTo(SeoUri::class, 'uri', 'uri');
    }

    /**
     * @return string
     */
    public function getMetaContentAttribute()
    {
        return $this->getMetaContent($this->meta_json, $this->is_minify);
    }
}
