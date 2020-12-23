<?php

namespace LaraAreaSeo\Traits;

trait TranslationTrait
{
    public function initializeTranslationTrait()
    {
        $this->actions = [
            'edit',
            'show',
            'translations.index' => [
                'label' => 'Translations'
            ],
            'destroy'
        ];
    }

    /**
     * @var
     */
    protected $translateable;

    /**
     * @return mixed
     */
    public function translations()
    {
        return $this->hasMany(get_class($this), 'parent_id');
    }

    /**
     * @return mixed
     */
    public function getTranslateAbleColumns()
    {
        return $this->translateable ?? $this->getFillable();
    }

    /**
     * @return mixed
     */
    public function main()
    {
        return $this->belongsTo(get_class($this), 'parent_id');
    }

    /**
     * @return mixed
     */
    public function getTranslationAttribute()
    {
        return $this->translations->first();
    }

    /**
     * @param $group
     * @return array
     */
    public function getActions($group = self::PAGINATE_GROUP)
    {
        if ($group == \ConstIndexableGroup::TRANSLATIONS) {
            if ($this->actions) {
                unset($this->actions['translations.index']);
            } else {
                $this->actions = ['edit', 'show', 'destroy'];
            }
        }

        return $this->actions ?  [
            'list' => $this->processActions(),
            'is_separate' => false,
            'label' => 'Actions'
        ]
            : [];
    }

    /**
     * @return mixed|string
     */
    public function getLanguageAttribute()
    {
        $languages = config('languages');
        return $languages[$this->attributes['lang']] ?? 'Unknown';
    }

    /**
     * @param bool $makeProperty
     * @param null $lang
     */
    public function translate($makeProperty = true, $lang = null)
    {
        if (key_exists('translations', $this->relations)) {
            $this->traslation = $this->translations->first();
        } else {
            $this->traslation = null;
        }
        $translationAbleColumns = $this->getTranslateAbleColumns();
        if ($makeProperty) {
            foreach ($translationAbleColumns as $column) {
                if (key_exists($column, $this->attributes)) {
                    $this->{$column . '_translated'} = $this->traslation->{$column} ?? $this->{$column};
                }
            }
        } else {
            foreach ($translationAbleColumns as $column) {
                if (key_exists($column, $this->attributes)) {
                    $this->{$column} = $this->traslation->{$column} ?? $this->{$column};
                }
            }
        }
    }
}
