<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/24
 * Time: 上午11:54
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug;

use FastD\Debug\Exceptions\FatalError;
use FastD\Debug\Exceptions\ServerInternalErrorException;

/**
 * Class ErrorHandler
 *
 * @package FastD\Debug
 */
class ErrorHandler
{
    /**
     * @var Debug
     */
    protected $debug;

    /**
     * ErrorHandler constructor.
     *
     * @param Debug|null $debug
     */
    public function __construct(Debug $debug = null)
    {
        $this->debug = $debug;
    }

    /**
     * @param $code
     * @param $message
     * @param $file
     * @param $line
     * @throws ServerInternalErrorException
     */
    public function handle($code, $message, $file, $line)
    {
        unset($code);
        $serverInternalErrorException = new ServerInternalErrorException($message);
        $serverInternalErrorException->setFile($file);
        $serverInternalErrorException->setLine($line);
        throw $serverInternalErrorException;
    }

    /**
     * @return void
     */
    public function handleFatalError()
    {
        $error = error_get_last();
        if($error){
            $serverInternalErrorException = new FatalError($error['message']);
            $serverInternalErrorException->setFile($error['message']);
            $serverInternalErrorException->setLine($error['line']);
            $this->debug->output(new Wrapper($serverInternalErrorException));
        }
    }

    /**
     * @param Debug|null $debug
     * @return static
     */
    public static function registerHandle(Debug $debug = null)
    {
        $handle = new static($debug);

        set_error_handler([$handle, 'handle']);

        register_shutdown_function([$handle, 'handleFatalError']);

        return $handle;
    }
}