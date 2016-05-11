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

use Monolog\Logger;
use FastD\Debug\Style\Wrapper;

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
     * @var array
     */
    protected $errorPage = [];

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param bool|true   $display
     * @param Logger|null $logger
     */
    public function __construct($display = true, Logger $logger = null)
    {
        $this->display = $display;

        $this->setLogger($logger);

        if ('cli' !== php_sapi_name()) {
            ini_set('display_errors', 0);

        } elseif (!ini_get('log_errors') || ini_get('error_log')) {
            ini_set('display_errors', 1);
        }
    }

    /**
     * @param Logger $logger
     * @return $this
     */
    public function setLogger(Logger $logger = null)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return boolean
     */
    public function isDisplay()
    {
        return $this->display;
    }

    /**
     * @param $code
     * @param $content
     * @return $this
     */
    public function setErrorPage($code, $content)
    {
        $this->errorPage[$code] = $content;

        return $this;
    }

    /**
     * @param $code
     * @return bool|string
     */
    public function getErrorPage($code)
    {
        if (!$this->hasErrorPage($code)) {
            return false;
        }

        if (file_exists($this->errorPage[$code])) {
            return file_get_contents($this->errorPage[$code]);
        }

        return $this->errorPage[$code];
    }

    /**
     * @param $code
     * @return bool
     */
    public function hasErrorPage($code)
    {
        return isset($this->errorPage[$code]) ? $this->errorPage[$code] : false;
    }

    /**
     * @param bool|true   $display
     * @param Logger|null $logger
     * @return Debug
     */
    public static function enable($display = true, Logger $logger = null)
    {
        if (null === static::$debug) {
            static::$debug = new static($display, $logger);
            Handler::register(static::$debug);
        }

        return static::$debug;
    }

    /**
     * @param Wrapper $wrapper
     * @return int|void
     */
    public function output(Wrapper $wrapper)
    {
        if (!$this->isDisplay() && null !== $this->logger) {
            $this->logger->error($wrapper->getStyleSheet()->getMessage(), [
                'file' => $wrapper->getThrowable()->getFile(),
                'line' => $wrapper->getThrowable()->getLine(),
                'status' => $wrapper->getStyleSheet()->getStatusCode(),
                'get' => $_GET,
                'post' => $_POST
            ]);
        }

        return $wrapper->send();
    }
}