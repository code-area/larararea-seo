<?php

namespace LaraAreaSeo\Models;

/**
 * Class SeoRoute
 * @package LaraAreaSeo\Models
 */
class SeoRoute extends BaseModel
{
    // @TODO use id, not url in information, seo
    /**
     * @var string
     */
    protected $table = 'seo_routes';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'template',
        'tags',
        'uri',
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    /**
     * @var array
     */
    protected $paginateable = [
        'name' => [
            'search' => true
        ],
        'uri',
        'template',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seo_uris()
    {
        return $this->hasMany(SeoUri::class, 'route_name', 'name');
    }

    /**
     * @return array
     */
    protected function getAllTagsAttribute()
    {
        $tags = array_merge(['url'], $this->tags ?? []);

        $parts = explode('/:', str_replace('?', '', $this->uri));
        array_shift($parts);
        foreach ($parts as $part) {
            $tags[] = $part;
        }

        return $tags;
    }
}
