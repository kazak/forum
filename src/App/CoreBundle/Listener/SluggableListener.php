<?php

/**
 * @author:     aat <aat@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 03 08 2015
 */
namespace App\CoreBundle\Listener;

/**
 * Class SluggableListener
 * @package App\CoreBundle\Listener
 */
class SluggableListener extends \Gedmo\Sluggable\SluggableListener
{
    /**
     * Listener construct to translate
     */
    public function __construct()
    {
        $this->setTransliterator(['\App\CoreBundle\Handler\TransliteratorHandler', 'transliterate']);
    }

    /**
     * @return string
     */
    protected function getNamespace()
    {
        return parent::getNamespace();
    }
}
