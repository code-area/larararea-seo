<?php

namespace LaraAreaSeo\Traits;

trait MetaTagTrait
{
    /**
     * @param $data
     * @param bool $isMinify
     * @return array
     */
    protected function getTags($data, $isMinify = false)
    {
        $content = $this->getMetaContent($data, $isMinify);
        preg_match_all('#\[(.*?)\]#', $content, $matches);
        $tags = $matches[1];
        $tags = array_unique($tags);
        $tags = array_values($tags);

        return $tags;
    }

    /**
     * @param $data
     * @param bool $isMinify
     * @return string
     */
    protected function getMetaContent($data, $isMinify = false)
    {
        $content = '';

        if (! empty($data['title'])) {
            $content .= sprintf('<title>%s</title>', $data['title']);
            if ($isMinify) {
                $content .= PHP_EOL;
            }
        }

        foreach ($data['metas'] as $metaData) {
            if (is_string($metaData)) {
                $content .= $metaData;
                if ($isMinify) {
                    $content .= PHP_EOL;
                }
                continue;
            }

            $content .= '<meta';
            foreach ($metaData as $attribute => $value) {
                $content .= sprintf(' %s="%s"', $attribute, $value);
            }
            $content .= '>';
            if ($isMinify) {
                $content .= PHP_EOL;
            }
        }

        return $content;
    }
}
