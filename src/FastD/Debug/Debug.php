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

/**
 * Class Debug
 *
 * @package FastD\Debug
 */
class Debug extends HttpStatusCode
{
    /**
     * @var bool
     */
    public static $enable = false;

    public static $enableBar = true;

    /**
     * @var \FastD\Logger\Logger
     */
    public static $logger;

    /**
     * @var array
     */
    public static $html = [];

    /**
     * @param \FastD\Logger\Logger|null $logger
     * @param array                     $config
     */
    public static function enable(\FastD\Logger\Logger $logger = null, array $config = [])
    {
        if (static::$enable) {
            return;
        }

        error_reporting(E_ALL);

        static::$logger = $logger;

        static::$enable = true;

        static::html();

        if (!empty($config)) {
            foreach ($config as $code => $value) {
                static::$html[$code] = $value;
            }
        }

        ExceptionHandler::registerHandle();

        ErrorHandler::registerHandle();
    }

    public static function html()
    {
        static::$html = [
            Debug::HTTP_NOT_FOUND               => __DIR__ . '/Html/404.html',
            Debug::HTTP_INTERNAL_SERVER_ERROR   => __DIR__ . '/Html/500.html',
            Debug::HTTP_FOUND                   => __DIR__ . '/Html/302.html',
        ];
    }

    public static function enableDebugBar($show = true)
    {
        static::$enableBar = $show;
    }

    protected static function showDebugBar()
    {

    }

    /**
     * @param ContextHandler $handler
     * @return void
     */
    public static function output(ContextHandler $handler)
    {
        if (null !== static::$logger) {
            $context = [];
            $context['line'] = $handler->getLine();
            $context['file'] = $handler->getFile();
            $context['_GET'] = $handler->getContext()['_GET'];
            $context['_POST'] = $handler->getContext()['_POST'];
            static::$logger->error($handler->getMessage(), $context);
        }

        if (!headers_sent()) {
            header(sprintf('HTTP/1.1 %s', $handler->getStatusCode()));
            foreach ($handler->getHeaders() as $name => $value) {
                header($name.': '.$value, false);
            }
        }

        echo $handler->getContent();
    }
}