<?php

namespace AppBundle\Listener;

/**
 * Class SluggableListener
 * @package AppBundle\Listener
 */
class SluggableListener extends \Gedmo\Sluggable\SluggableListener
{
    /**
     * Listener construct to translate
     */
    public function __construct()
    {
        $this->setTransliterator(['AppBundle\Handler\TransliteratorHandler', 'transliterate']);
    }

    /**
     * @return string
     */
    protected function getNamespace()
    {
        return parent::getNamespace();
    }
}
