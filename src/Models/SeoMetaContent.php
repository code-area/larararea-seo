<?php

namespace LaraAreaSeo\Models;

/**
 * Class SeoMetaContent
 * @package LaraAreaSeo\Models
 */
class SeoMetaContent extends TranslateAbleModel
{
    protected $descriptiveAttribute = 'meta_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'seo_id',
        'meta_id',
        'meta_group_id',
        'is_active',
        'lang',
        'content',
    ];

    /**
     * @var array
     */
    protected $paginateable = [
        'seo_id',
        'lang' => [
            'attribute' => 'language',
            'group' => \ConstIndexableGroup::TRANSLATIONS
        ],
        'meta_id',
        'meta_group_id',
        'is_active',
        'content' => [
            'group' => [
                \ConstIndexableGroup::TRANSLATIONS,
                \ConstIndexableGroup::INDEX,
            ]
        ],
    ];

    public function meta()
    {
        return $this->belongsTo(Meta::class);
    }
    public function meta_group()
    {
        return $this->belongsTo(MetaGroup::class);
    }
}
