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

use DebugBar\DebugBar;
use DebugBar\StandardDebugBar;
use Monolog\Logger;

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

    /**
     * @var Logger
     */
    public static $logger;

    /**
     * @var array
     */
    public static $html = [];

    /**
     * @var DebugBar
     */
    public static $debugBar;

    /**
     * @param Logger $logger
     * @param array                     $config
     */
    public static function enable(Logger $logger = null, array $config = [])
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

    /**
     * @return void;
     */
    public static function html()
    {
        static::$html = [
            Debug::HTTP_NOT_FOUND               => __DIR__ . '/Html/404.html',
            Debug::HTTP_INTERNAL_SERVER_ERROR   => __DIR__ . '/Html/500.html',
            Debug::HTTP_FOUND                   => __DIR__ . '/Html/302.html',
        ];
    }

    /**
     * @return DebugBar
     */
    private static function getDebugBar()
    {
        if (null === static::$debugBar) {
            static::$debugBar = new StandardDebugBar();
        }

        return static::$debugBar;
    }

    /**
     * @param $vars
     */
    public static function dump($vars)
    {
        static::$debugBar = static::getDebugBar();

        static::$debugBar['messages']->addMessage($vars);
    }

    /**
     * @param string $resources
     * @param array  $context
     */
    public static function showDebugBar($resources = './debugbar', array $context = [])
    {
        static::$debugBar = static::getDebugBar();

        foreach ($context as $value) {
            static::$debugBar['messages']->addMessage($value);
        }

        $render = static::$debugBar->getJavascriptRenderer()
            ->setBaseUrl($resources)
            ->setEnableJqueryNoConflict(false);

        $renderFunc = function () use ($render) {
            $html = <<<EOF
<html>
    <head>
        {$render->renderHead()}
    </head>
    <body>
    {$render->render()}
    </body>
</html>
EOF;
            return $html;
        };

        echo $renderFunc();
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
            static::$logger->addError($handler->getMessage(), $context);
        }

        if (!headers_sent()) {
            header(sprintf('HTTP/1.1 %s', $handler->getStatusCode()));
            foreach ($handler->getHeaders() as $name => $value) {
                header($name . ': ' . $value, false);
            }
        }

        echo $handler->getContent();
    }
}