<?php

namespace LaraAreaSeo\Models;

/**
 * Class MetaGroup
 * @package LaraAreaSeo\Models
 */
class MetaGroup extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'headline',
        'starts_with',
        'is_active',
        'comment_start',
        'comment_end'
    ];

    protected $paginateable = [
        'headline',
        'starts_with',
        'comment_start',
        'comment_end',
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

    /**
     * @return mixed
     */
    public function meta_group_metas()
    {
        return $this->belongsToMany(MetaGroup::class, 'meta_group_meta');
    }
}
