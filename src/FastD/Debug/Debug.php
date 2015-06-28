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

class Debug
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

        ExceptionHandler::registerHandle();

        ErrorHandler::registerHandle();
    }

    public static function output(ContextHandler $context)
    {
        if (null !== Debug::getLogger()) {
            $context = [];
            $context['line'] = $context->getLine();
            $context['file'] = $context->getFile();
            $context['_GET'] = $context->getContext()['_GET'];
            $context['_POST'] = $context->getContext()['_POST'];
            Debug::getLogger()->error($context->getMessage(), $context);
        }

        if (!headers_sent()) {
            header(sprintf('HTTP/1.1 %s', $context->getStatusCode()));
            foreach ($context->getHeaders() as $name => $value) {
                header($name.': '.$value, false);
            }
        }

        echo $context->getContent();
    }
}