<?php

namespace LaraAreaSeo\Validators;

use LaraAreaSeo\Cache\CachedSeo;
use LaraAreaSeo\Models\Seo;
use LaraAreaSeo\Models\SeoConfig;

class SeoValidator extends MetaValidator
{
    public function create()
    {
        $seoConfig = CachedSeo::seoConfig();
        $metas = CachedSeo::metas();

        $rules = [
            'headline' => 'required|not_in:' . SeoConfig::HEADLINE,
            'uri' => 'different_when:route_name,' . SeoConfig::ROUTE_NAME . ',' . SeoConfig::URI,
            'route_name' => 'different_when:uri,' . SeoConfig::URI . ',' . SeoConfig::ROUTE_NAME,
            'active' => 'in:0,1',
            'title' => $this->ruleBased($seoConfig, 'title'),
            'robots' => $this->ruleBased($seoConfig, 'robots'),
            'description' => $this->ruleBased($seoConfig, 'description'),
            'keywords' => $this->ruleBased($seoConfig, 'keywords'),
            'with.seo_meta_contents.*' => function ($attribute, $value, $fails) use($metas) {
                $meta = $metas->where('id', $value['meta_id'])->first();
                $metaGroupId = $value['meta_group_id'];
                $metaGroup = $meta->meta_groups->where('id', $metaGroupId)->first();

                $attribute = $meta->attribute . ': ' . ($metaGroup ? $metaGroup->starts_with : '') . $meta->attribute_value;

                if ($meta->is_required && empty($value['content'])) {
                    if (! empty($value['meta_group_id'])) {
                        if ($meta->is_required_in_group) {
                            return $fails(__('validation.required', ['attribute' => $attribute]));
                        }
                    } else {
                        return $fails(__('validation.required', ['attribute' => $attribute]));
                    }
                }

                if ($meta->min && !empty($value['content']) && strlen($value['content']) < $meta->min) {
                    return $this->validateContent($attribute, $value['content'], $meta->min, true, $fails);
                }

                if ($meta->max && !empty($value['content']) && strlen($value['content']) > $meta->max) {
                    return $this->validateContent($attribute, $value['content'], $meta->max, false, $fails);
                }
            }
        ];


        return $rules;
    }

    public function ruleBased($seoConfig, $column)
    {
        $rules = [];
        $min = request($column . '_min') ?? $seoConfig->{$column . '_min'} ?? 0;
        $max = request($column . '_max') ?? $seoConfig->{$column . '_max'} ?? 0;
        $required = request($column . '_required') ?? $seoConfig->{$column . '_required'} ?? 0;
        $isNullable = true;

        if ($required) {
            $isNullable = false;
            $rules[] = 'required';
        }

        if ($min) {
            if ($isNullable) {
                $rules[] = 'nullable';
            }
            $rules[] = function ($attribute, $value, $fails) use ($min) {
                $this->validateContent($attribute, $value, $min, true, $fails);
            };
        }

        if ($max) {
            if ($isNullable && ! in_array('nullable', $rules)) {
                $rules[] = 'nullable';
            }
            $rules[] = function ($attribute, $value, $fails) use ($max) {
                $this->validateContent($attribute, $value, $max, false, $fails);
            };
        }
        return $rules;
    }

    public function update()
    {
        return $this->create();
    }
    public function translation()
    {
        return [
            'lang' => [
                'required',
                'uniqueTranslation:' . Seo::class . ',parent_id',
            ],
            'title' => 'required',
        ];
    }
}
