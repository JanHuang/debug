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
    /**
     * @var Debug
     */
    protected static $debug;

    /**
     * @var bool
     */
    protected $display = true;

    /**
     * @var ExceptionHandler
     */
    protected $exceptionHandle;

    /**
     * @var ErrorHandler
     */
    protected $errorHandle;

    /**
     * @var DebugBar
     */
    protected $debugBar;

    /**
     * @var bool
     */
    protected $showDebugBar = false;

    /**
     * @var array
     */
    protected $custom = [];

    /**
     * @var Logger
     */
    protected $logger;

    protected $cli = true;

    /**
     * @return boolean
     */
    public function isCli()
    {
        return $this->cli;
    }

    /**
     * @param bool|true   $display
     * @param Logger|null $logger
     */
    public function __construct($display = true, Logger $logger = null)
    {
        $this->display = $display;

        $this->handleLogger($logger);

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
     * @param Logger $logger
     * @return $this
     */
    public function handleLogger(Logger $logger = null)
    {
        $this->logger = $logger;
        return $this;
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
     * @param $code
     * @param $content
     * @return $this
     */
    public function setCustom($code, $content)
    {
        $this->custom[$code] = $content;

        return $this;
    }

    /**
     * @param $code
     * @return bool|string
     */
    public function getCustom($code)
    {
        if (!$this->hasCustom($code)) {
            return false;
        }

        if (file_exists($this->custom[$code])) {
            return file_get_contents($this->custom[$code]);
        }

        return $this->custom[$code];
    }

    /**
     * @param $code
     * @return bool
     */
    public function hasCustom($code)
    {
        return isset($this->custom[$code]) ? $this->custom[$code] : false;
    }

    /**
     * @param bool|true   $display
     * @param Logger|null $logger
     * @return Debug
     */
    public static function enable($display = true, Logger $logger = null)
    {
        if (static::$debug instanceof Debug) {
            return static::$debug;
        }

        static::$debug = new static($display, $logger);

        if ('cli' !== php_sapi_name()) {
            ini_set('display_errors', 0);
            static::$debug->cli = false;
        } elseif (!ini_get('log_errors') || ini_get('error_log')) {
            ini_set('display_errors', 1);
        }

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

        return $renderFunc();
    }

    /**
     * @param Wrapper $wrapper
     * @return int|void
     */
    public function output(Wrapper $wrapper)
    {
        if ($this->isCli()) {

            $path = $wrapper->getFile() . ': ' . $wrapper->getLine();
            $length = strlen($path);

            if ('WIN' === strtoupper(substr(PHP_OS, 0, 3))) {
                echo PHP_EOL;
                echo PHP_EOL;
                echo '[' . $wrapper->getName() . ']' . PHP_EOL;
                echo $wrapper->getMessage();
                echo $path . PHP_EOL;
                echo  PHP_EOL;
                return 1;
            }

            echo PHP_EOL;
            echo chr(27) . '[41m' . str_repeat(' ', $length + 6) . chr(27) . "[0m" . PHP_EOL;
            echo chr(27) . '[41m   ' . '[' . $wrapper->getName() . ']   ' . str_repeat(' ', ($length - strlen($wrapper->getName()) - 2)) . chr(27) . "[0m" . PHP_EOL;
            echo chr(27) . '[41m   ' . $wrapper->getMessage() . str_repeat(' ', $length - $wrapper->getMessage() - 4) . '   ' . chr(27) . "[0m" . PHP_EOL;
            echo chr(27) . '[41m   ' . $path . '   ' . chr(27) . "[0m" . PHP_EOL;
            echo chr(27) . '[41m' . str_repeat(' ', $length + 6) . chr(27) . "[0m" . PHP_EOL;
            echo PHP_EOL;
            return 1;
        }

        if (!headers_sent()) {
            header(sprintf('HTTP/1.1 %s', $wrapper->getStatusCode()));
            foreach ($wrapper->getHeaders() as $name => $value) {
                header($name . ': ' . $value, false);
            }
        }

        if ($this->logger instanceof Logger && !$this->isDisplay()) {
            $this->logger->addError($wrapper->getMessage(), [
                'FILE: ' => $wrapper->getFile(),
                'LINE: ' => $wrapper->getFile(),
                'GET: ' => $_GET,
                'POST: ' => $_POST,
            ]);
        }

        echo $wrapper;

        if ($this->isDisplay() && !$this->isShowDebugBar() && !$wrapper->isApplicationJson()) {
            $this->showDebugBar();
        }
    }
}