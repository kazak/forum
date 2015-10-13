<?php

/**
 * @author      : aat <aat@norse.digital>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date        : 03 08 2015
 */
namespace App\CoreBundle\Handler;

use Gedmo\Sluggable\Util\Urlizer;

/**
 * Class TransliteratorHandler
 * @package App\CoreBundle\Handler
 */
class TransliteratorHandler extends Urlizer
{
    private static $table = [
        "'" => '',
    ];

    /**
     * @param string $text
     * @param string $separator
     * @return string
     */
    public static function transliterate($text, $separator = '-')
    {
        $text = strtr($text, self::$table);
        if (preg_match('/[\x80-\xff]/', $text) && self::validUtf8($text)) {
            $text = self::utf8ToAscii($text);
        }

        return self::urlize($text, $separator);
    }
}
