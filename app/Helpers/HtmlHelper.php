<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/9/19
 * Time: 3:50 PM
 */

namespace App\Helpers;


class HtmlHelper
{

    /**
     * @var self
     */
    private static $instance;

    private $helper = [];

    private function __construct(){}

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function bind(string $name, $callback)
    {
        self::getInstance()->helper[$name] = $callback;
    }

    public static function __callStatic($name, $arguments)
    {
        $callback = \Arr::get(self::getInstance()->helper, $name);
        if (is_callable($callback)) {
            return call_user_func($callback, ...$arguments);
        }
        return $callback;
    }


}