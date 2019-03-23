<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/9/19
 * Time: 3:44 PM
 */

namespace App\Helpers;


use Carbon\Language;

class LanguageHelper
{
    /**
     * @param string $code
     * @param string $type isoName or nativeName default: isoName
     * @return string
     */
    public static function getLanguageByCode($code, $type = 'isoName')
    {
        return (string) \Arr::get(Language::all(), "${code}.${type}");
    }
}