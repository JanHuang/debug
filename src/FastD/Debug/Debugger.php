<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/22
 * Time: 上午11:04
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug;

use FastD\Logger\Logger;

class Debugger
{
    protected static $enable = false;

    protected static $logger;

    /**
     * @return Logger
     */
    public static function getLogger()
    {
        return static::$logger;
    }

    public static function enable(Logger $logger = null)
    {
        if (static::$enable) {
            return;
        }

        static::$logger = $logger;

        static::$enable = true;

        error_reporting(E_ALL);

        ExceptionHandle::registerHandle();

        ErrorHandle::registerHandle();
    }
}