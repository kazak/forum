<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 31.03.16
 * Time: 15:44
 */

namespace CoreBundle\Handler;

use Gedmo\Sluggable\Util\Urlizer;

/**
 * Class TransliteratorHandler
 * @package CoreBundle\Handler
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