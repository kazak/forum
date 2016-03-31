<?php

namespace CoreBundle\Listener;

/**
 * Class SluggableListener
 * @package CoreBundle\Listener
 */
class SluggableListener extends \Gedmo\Sluggable\SluggableListener
{
    /**
     * Listener construct to translate
     */
    public function __construct()
    {
        $this->setTransliterator(['CoreBundle\Handler\TransliteratorHandler', 'transliterate']);
    }

    /**
     * @return string
     */
    protected function getNamespace()
    {
        return parent::getNamespace();
    }
}
