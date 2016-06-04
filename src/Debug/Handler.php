<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/4/21
 * Time: 下午9:36
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug;

use FastD\Debug\Exceptions\FatalError;
use FastD\Debug\Style\Wrapper;

/**
 * Class Handler
 *
 * @package FastD\Debug
 */
class Handler
{
    /**
     * @var Debug
     */
    protected $debug;

    /**
     * Handler constructor.
     * @param Debug $debug
     */
    private function __construct(Debug $debug)
    {
        $this->debug = $debug;

        set_exception_handler([$this, 'exceptionHandle']);

        register_shutdown_function([$this, 'fatalErrorHandler']);

        if (null === $prev = set_error_handler([$this, 'errorHandle'])) {
            restore_error_handler();

            set_error_handler([$this, 'errorHandle']);
        };

        restore_error_handler();
    }

    final private function __clone(){}

    /**
     * @param $code
     * @param $message
     * @param $file
     * @param $line
     * @throws FatalError
     */
    public function errorHandle($code, $message, $file, $line)
    {
        unset($code);
        $serverInternalErrorException = new FatalError($message);
        $serverInternalErrorException->setFile($file);
        $serverInternalErrorException->setLine($line);
        throw $serverInternalErrorException;
    }

    /**
     * @return void
     */
    public function fatalErrorHandler()
    {
        $error = error_get_last();

        if ($error && $error['type'] == E_ERROR) {
            $serverInternalErrorException = new FatalError($error['message']);
            $serverInternalErrorException->setFile($error['message']);
            $serverInternalErrorException->setLine($error['line']);
            $this->debug->output(new Wrapper($serverInternalErrorException, $this->debug->isDisplay()));
        }
    }

    /**
     * @param \Throwable $throwable
     */
    public function exceptionHandle(\Throwable $throwable)
    {
        $this->debug->output(new Wrapper($throwable, $this->debug->isDisplay()));
    }

    /**
     * @param Debug $debug
     * @return static
     */
    public static function register(Debug $debug)
    {
        return new static($debug);
    }
}