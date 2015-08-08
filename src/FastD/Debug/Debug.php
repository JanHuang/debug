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
class Debug
{
    protected static $debug;

    protected $display = true;

    protected $exceptionHandle;

    protected $errorHandle;

    public function __construct($display = true)
    {
        $this->display = $display;

        $this->exceptionHandle = ExceptionHandler::registerHandle($this);

        $this->errorHandle = ErrorHandler::registerHandle($this);
    }

    /**
     * @return ErrorHandler
     */
    public function getErrorHandle()
    {
        return $this->errorHandle;
    }

    /**
     * @return ExceptionHandler
     */
    public function getExceptionHandle()
    {
        return $this->exceptionHandle;
    }

    /**
     * @return boolean
     */
    public function isDisplay()
    {
        return $this->display;
    }

    /**
     * @param bool|true $display
     * @return Debug
     */
    public static function enable($display = true)
    {
        if (static::$debug instanceof Debug) {
            return static::$debug;
        }

        static::$debug = new static($display);

        return static::$debug;
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
    public function dump($vars)
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

        static::$debugBar['messages']->addMessage($context);

        $render = static::$debugBar->getJavascriptRenderer()
            ->setBaseUrl($resources)
            ->setEnableJqueryNoConflict(true);

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

    public function output(Wrapper $wrapper)
    {
        if (!headers_sent()) {
            header(sprintf('HTTP/1.1 %s', $wrapper->getStatusCode()));
//            foreach ($handler->getHeaders() as $name => $value) {
//                header($name . ': ' . $value, false);
//            }
        }

        echo $wrapper;
    }
}