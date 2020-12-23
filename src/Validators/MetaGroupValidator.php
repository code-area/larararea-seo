<?php

namespace LaraAreaSeo\Validators;

class MetaGroupValidator extends BaseValidator
{
    public function create()
    {
        return [
            'headline' => 'required',
            'starts_with' => 'required',
            'is_active' => 'in:0,1',
        ];
    }
}
