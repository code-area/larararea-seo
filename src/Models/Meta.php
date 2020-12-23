<?php

namespace LaraAreaSeo\Models;

/**
 * Class Meta
 * @package LaraAreaSeo\Models
 */
class Meta extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attribute',
        'attribute_value',
        'default_content',
        'is_active',
        'only_in_groups',
        'min',
        'max',
        'is_required',
        'is_required_in_group',
    ];

    protected $paginateable = [
        'attribute',
        'attribute_value' => [
            'search' => true
        ],
        'default_content',
        'only_in_groups',
        'is_active' => [
            'attribute' => 'active'
        ]
    ];

    /**
     * @return mixed
     */
    public function getActiveAttribute()
    {
        return $this->attributes['is_active'] ? 'Active' : 'In Active';
    }

    public function meta_groups()
    {
        return $this->belongsToMany(MetaGroup::class, 'meta_group_meta')->withPivot('default_content');
    }
}
