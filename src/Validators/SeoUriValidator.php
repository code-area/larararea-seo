<?php

namespace LaraAreaSeo\Validators;

use Illuminate\Support\Str;
use LaraAreaSeo\Cache\CachedSeo;

class SeoUriValidator extends BaseValidator
{
    /**
     * @return array
     */
    public function create()
    {
        return [
            'route_name' => 'bail|required',
            'uri' => [
                'required',
                'unique' => 'unique:seo_uris,uri',
                function ($attribute, $value, $fails) {
                    $seoRoutes = CachedSeo::seoRoutes()->where('name', request('route_name'))->first();
                    $uri = $seoRoutes->uri;
                    $this->validateUri($uri, [$value], $fails);
                }
            ],
            'is_active' => $this->isCheckbox(),
        ];
    }

    /**
     * @param $uri
     * @param $values
     * @param $fails
     * @return mixed
     */
    protected function validateUri($uri, $values, $fails)
    {
        $parts = explode('/', $uri);
        if (count($parts) == 1) {
            return $fails('This route can not have uris');
        }

        $errors = [];
        foreach ($values as $value) {
            if ($uri == $value) {
                $errors[] = sprintf('This [%s] uri is equal main uri', $value);
                continue;
            }

            $valueParts = explode('/', $value);
            foreach ($parts as $index => $part) {
                if (Str::startsWith($part, ':')) {
                    if (Str::endsWith($part, '?')) {
                        continue 2;
                    } else {
                        if (!isset($valueParts[$index])) {
                            $errors[] = sprintf('This [%s] uri is not valid', $value);
                            continue 2;
                        }
                    }
                } else {
                    if (!isset($valueParts[$index]) || $valueParts[$index] != $part) {
                        $errors[] = sprintf('This [%s] uri is not valid', $value);
                        continue 2;
                    }
                }
            }
        }

        if ($errors) {
            return $fails($errors);
        }
    }
}
