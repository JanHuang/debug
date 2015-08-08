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

    protected $debugBar;

    protected $showDebugBar = false;

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
     * @return boolean
     */
    public function isShowDebugBar()
    {
        return $this->showDebugBar;
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
    public function getDebugBar()
    {
        if (null === $this->debugBar) {
            $this->debugBar = new StandardDebugBar();
        }

        return $this->debugBar;
    }

    /**
     * @param $vars
     */
    public function dump($vars)
    {
        $this->getDebugBar()['messages']->addMessage($vars);
    }

    /**
     * @param array  $context
     * @param string $resources
     */
    public function showDebugBar(array $context = [], $resources = 'http://resources.fast-d.cn/debugbar')
    {
        $this->showDebugBar = true;

        $debagBar = $this->getDebugBar();

        $debagBar['messages']->addMessage($context);

        $render = $debagBar->getJavascriptRenderer()
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
            foreach ($wrapper->getHeaders() as $name => $value) {
                header($name . ': ' . $value, false);
            }
        }

        echo $wrapper;

        if ($this->isDisplay() && !$this->isShowDebugBar()) {
            $this->showDebugBar();
        }
    }
}