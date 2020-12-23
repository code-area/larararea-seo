<?php

namespace LaraAreaSeo\Validators;

class MetaValidator extends BaseValidator
{
    public function create()
    {
        return [
            'min' => 'nullable|numeric|min:0',
            'max' => 'bail|nullable|numeric|min:1|greaterOrEqual:min',
            'attribute' => 'required|in:name,property',
            'attribute_value' => 'required',
            'default_content' => [
                'required',
                function ($attribute, $value, $fails) {
                    $min = request('min');
                    $max = request('max');

                    if ($min) {
                        $this->validateContent($attribute, $value, $min, true, $fails);
                    }

                    if ($max) {
                        $this->validateContent($attribute, $value, $max, false, $fails);
                    }
                }
            ],
            'is_active' => $this->isCheckbox(),
            'is_required' => $this->isCheckbox(),
            'only_in_groups' => $this->isCheckbox(),
            // TODO validate
//            'with.meta_groups.*' => function ($attribute, $value, $fails) {
//                return $fails('test');
//            }
        ];
    }

    /**
     * @param $attribute
     * @param $value
     * @param $length
     * @param $isMin
     * @param $fails
     * @return bool
     */
    protected function validateContent($attribute, $value, $length, $isMin, $fails)
    {
        preg_match_all('#\[(.*?)\]#', $value, $matches);
        $tags = $matches[1];

        if (empty($tags)) {
            if ($isMin) {
                if (strlen($value) < $length) {
                    return $fails(__('validation.min.string', ['attribute' => $attribute, 'min' => $length]));
                }
            } else {
                if (strlen($value) > $length) {
                    return $fails(__('validation.max.string', ['attribute' => $attribute, 'max' => $length]));
                }
            }

            return true;
        }

        if ($isMin) {
            return true;
        }

        foreach ($tags as $tag) {
            $value = str_replace($tag, '', $value);
        }

        if (strlen($value) > $length) {
            return $fails(__('validation.max.string', ['attribute' => 'dynamic ' . $attribute, 'max' => $length]));
        }

        return true;
    }
}
