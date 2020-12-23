<?php

namespace LaraAreaSeo\Validators;

use LaraAreaSeo\Models\SeoConfig;

class SeoRouteValidator extends SeoUriValidator
{
    public function create()
    {
        return [
            'name' => 'required',
            'uri' => [
                'bail',
                function ($attribute, $value, $fails) {
                    if (empty($value) && request('name') != SeoConfig::HOME_ROUTE) {
                        $fails(__('validation:required'));
                    }
                },
            ],
            'template' => 'required',
            'is_active' => $this->isCheckbox(),
            'uris' => function ($attribute, $values, $fails) {
                $uri = request('uri');
                $this->validateUri($uri, $values, $fails);
            }
        ];
    }
}
