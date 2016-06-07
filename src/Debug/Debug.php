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

use DebugBar\DataCollector\ExceptionsCollector;
use DebugBar\DataCollector\MemoryCollector;
use DebugBar\DataCollector\MessagesCollector;
use DebugBar\DataCollector\PhpInfoCollector;
use DebugBar\DataCollector\RequestDataCollector;
use DebugBar\DataCollector\TimeDataCollector;
use FastD\Debug\Collectors\Collector;
use FastD\Debug\Theme\Symfony\StyleSheet;
use FastD\Debug\Theme\Theme;
use Monolog\Logger;
use ErrorException;
use Throwable;

/**
 * Class Debug
 *
 * @package FastD\Debug
 */
class Debug
{
    /**
     * @var static
     */
    protected static $handler;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * 依赖第三方组件: maximebf/debugbar
     *
     * @var DebugBar
     */
    protected $bar = DebugBar::class;

    /**
     * @var Collector[]
     */
    protected $collectors = [];
    
    /**
     * @var Theme|StyleSheet
     */
    protected $theme = StyleSheet::class;

    /**
     * @var bool
     */
    protected $display = true;

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * Debug constructor.
     * @param bool $display
     * @param Logger|null $logger
     */
    protected function __construct($display = true, Logger $logger = null)
    {
        if ('cli' === PHP_SAPI) {
            ini_set('display_errors', 0);
        } elseif (!ini_get('log_errors') || ini_get('error_log')) {
            ini_set('display_errors', 1);
        }

        $this->display = $display;

        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    public function isBooted()
    {
        return $this->booted;
    }

    /**
     * @return bool
     */
    public function isDisplay()
    {
        return $this->display;
    }

    /**
     * @return Logger|null
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param $theme
     * @return $this
     * @throws ErrorException
     */
    public function setTheme($theme)
    {
        if (!($theme instanceof Theme)) {
            throw new ErrorException(sprintf('Debug theme must be instance ["%s"]', Theme::class));
        }

        $this->theme = $theme;

        return $this;
    }

    /**
     * @return Theme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @return DebugBar
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param $data
     * @return $this
     */
    public function dump($data)
    {
        if (!$this->isDisplay()) {
            return;
        }

        $this->bar->getCollector('dump')->addMessage($data);

        return $this;
    }

    /**
     * @param bool $display
     * @param Logger|null $logger
     * @return Debug
     */
    public static function getHandler($display = true, Logger $logger = null)
    {
        if (null === static::$handler) {
            static::$handler = new static($display, $logger);
        }

        return static::$handler;
    }

    /**
     * @param bool $display
     * @param Logger|null $logger
     * @param array $collectors
     * @return Debug
     */
    public static function enable($display = true, Logger $logger = null, array $collectors = DebugBar::PRESET_COLLECTORS)
    {
        $handler = static::getHandler($display, $logger);

        if ($handler->isBooted()) {
            return $handler;
        }

        // 捕捉一切异常与错误
        static::enableError($handler);

        static::enableException($handler);

        if ($handler->isDisplay()) {
            static::enableDebugBar($collectors);
        }

        return $handler;
    }

    /**
     * @param Debug|null $debug
     * @return void
     */
    protected static function enableError(Debug $debug = null)
    {
        $handler = null === $debug ? static::getHandler() : $debug;

        register_shutdown_function([$handler, 'handleFatalError']);

        if ($prev = set_error_handler([$handler, 'handleError'])) {
            restore_error_handler();
            set_error_handler([$handler, 'handleError']);
        }

        unset($prev, $handler);
    }

    /**
     * @param Debug|null $debug
     * @return void
     */
    protected static function enableException(Debug $debug = null)
    {
        $handler = null === $debug ? static::getHandler() : $debug;

        if ($prev = set_exception_handler([$handler, 'handleException'])) {
            restore_exception_handler();
            set_exception_handler([$handler, 'handleException']);
        }

        unset($prev, $handler);
    }

    /**
     * @param array $collectors
     */
    protected static function enableDebugBar(array $collectors = DebugBar::PRESET_COLLECTORS)
    {
        $handler = static::getHandler();

        $handler->bar = new DebugBar($collectors);
    }

    /**
     * 将错误托管給 exception(handleException) 处理。
     *
     * @param $code
     * @param $message
     * @param $file
     * @param $line
     * @throws ErrorException
     */
    public function handleError($code, $message, $file, $line)
    {
        throw new ErrorException($message, $code, E_ERROR, $file, $line);
    }

    /**
     * 将错误托管給 exception(handleException) 处理。
     *
     * @throws ErrorException
     */
    public function handleFatalError()
    {
        if (($error = error_get_last()) && $error['type'] == E_ERROR) {
            self::handleError($error['type'], $error['message'], $error['file'], $error['line']);
        } else if ($this->bar instanceof DebugBar && $this->isDisplay()) {
            // Shutdown script running.
            // If enable debug mode. Can be auto show debugbar into html page.
            $isSend = (function () {
                $list = headers_list();
                foreach ($list as $value) {
                    list($name, $value) = explode(':', $value);
                    if ($name == 'Content-type' && (false !== strpos(trim($value), 'text/html'))) {
                        return true;
                    }
                }
                return false;
            })();
            if ($isSend) {
                $this->bar->output();
            }
        }
    }

    /**
     * 抛给 Wrapper 对象处理
     *
     * @param Throwable $throwable
     * @return void
     */
    public function handleException(Throwable $throwable)
    {
        if ($this->isDisplay()) {
            $this->getBar()->getCollector('exceptions')->addException($throwable);
        }

        $this->wrapper($throwable);
    }

    /**
     * @param Throwable $throwable
     * @return void
     */
    public function wrapper(Throwable $throwable)
    {
        Wrapper::output($this, $throwable);
    }
}