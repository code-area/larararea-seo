<?php

namespace LaraAreaSeo\Validators;

class SeoConfigValidator extends SeoValidator
{
    public function create()
    {
        $rules = [
            'title_min' => 'nullable|numeric|min:0',
            'title_max' => 'bail|nullable|numeric|min:1|greaterOrEqual:title_min',
            'title_required' => $this->isCheckbox(),
            'robots_min' => 'nullable|numeric|min:0',
            'robots_max' => 'bail|nullable|numeric|min:1|greaterOrEqual:robots_min',
            'robots_required' => $this->isCheckbox(),
            'description_min' => 'nullable|numeric|min:0',
            'description_max' => 'bail|nullable|numeric|min:1|greaterOrEqual:description_min',
            'description_required' => $this->isCheckbox(),
            'keywords_min' => 'nullable|numeric|min:0',
            'keywords_max' => 'bail|nullable|numeric|min:1|greaterOrEqual:keywords_min',
            'keywords_required' => $this->isCheckbox(),
        ];
        $parent = parent::create();
        unset($parent['headline']);

        return array_merge($rules, $parent);
    }

}
