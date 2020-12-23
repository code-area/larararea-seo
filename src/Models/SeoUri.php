<?php

namespace LaraAreaSeo\Models;

/**
 * Class SeoUri
 * @package LaraAreaSeo\Models
 */
class SeoUri extends BaseModel
{
    // @TODO use id, not url in information, seo
    /**
     * @var string
     */
    protected $table = 'seo_uris';

    /**
     * @var array
     */
    protected $fillable = [
        'uri',
        'route_name',
        'is_active',
        'tags',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'tags' => 'array'
    ];

    /**
     * @var array
     */
    protected $paginateable = [
        'route_name' => [
            'search' => true,
        ],
        'uri' => [
            'search' => true,
        ],
        'is_active',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seo_route()
    {
        return $this->belongsTo(SeoRoute::class, 'route_name', 'name');
    }
}
