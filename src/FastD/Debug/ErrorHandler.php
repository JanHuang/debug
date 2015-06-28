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

use FastD\Debug\Exceptions\BaseException;

class ErrorHandler
{
    public function handle($code, $message, $file, $line)
    {
        if (array_key_exists($code, Debug::$statusTexts)) {
            $code = 500;
        }

        throw new BaseException($message, $code);
    }

    public function handleFatalError(array $error = null)
    {

    }

    public static function registerHandle()
    {
        $handle = new static();

        set_error_handler([$handle, 'handle']);

        register_shutdown_function([$handle, 'handleFatalError']);

        return $handle;
    }
}