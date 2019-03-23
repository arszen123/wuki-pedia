<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/23/19
 * Time: 9:30 AM
 */

if (!function_exists('array_flat')) {
    function array_flat (array $array, $key) {
        $result = [];
        foreach ($array as $item) {
            $result[] = array_get($item, $key);
        }
        return $result;
    }
}