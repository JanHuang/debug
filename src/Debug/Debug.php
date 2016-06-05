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

use FastD\Debug\Theme\Symfony\StyleSheet;
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
     * @var ThemeInterface|StyleSheet
     */
    protected $theme = StyleSheet::class;

    /**
     * @var bool
     */
    protected $display = true;

    /**
     * @var Logger
     */
    protected $logger;

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
        error_reporting(null);

        if ('cli' !== PHP_SAPI) {
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
        if (!($theme instanceof ThemeInterface)) {
            throw new ErrorException(sprintf('Debug theme must be instance ["%s"]', ThemeInterface::class));
        }

        $this->theme = $theme;

        return $this;
    }

    /**
     * @return ThemeInterface
     */
    public function getTheme()
    {
        return is_object($this->theme) ? $this->theme : new $this->theme;
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
     * @param bool|true   $display
     * @param Logger|null $logger
     * @return Debug
     */
    public static function enable($display = true, Logger $logger = null)
    {
        $handler = static::getHandler($display, $logger);

        if ($handler->isBooted()) {
            return $handler;
        }

        // 捕捉一切异常与错误
        static::enableError($handler);

        static::enableException($handler);

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

        unset($prev);
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

        unset($prev);
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
        $this->wrapper($throwable);
    }

    /**
     * @param Throwable $throwable
     * @return void
     */
    public function wrapper(Throwable $throwable)
    {
        Wrapper::output($throwable);
    }
}