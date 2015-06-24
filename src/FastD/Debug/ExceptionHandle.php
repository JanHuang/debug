<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/23
 * Time: 下午11:53
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug;

use FastD\Debug\Handle\Handle;

/**
 * Class ExceptionHandle
 *
 * @package FastD\Debug
 */
class ExceptionHandle
{
    /**
     * @param \Exception $exception
     */
    public function handle(\Exception $exception)
    {
        $handle = Handle::create($exception);

        if (null !== Debugger::getLogger()) {
            $context = $handle->getContext();
            unset($context['_SERVER']);
            Debugger::getLogger()->error($handle->getMessage(), $context);
        }

        $this->sendFailResponse($handle);
    }

    /**
     * @return static
     */
    public static function registerHandle()
    {
        $handle = new static();

        set_exception_handler([$handle, 'handle']);

        return $handle;
    }

    /**
     * @param Handle $handle
     */
    public function sendFailResponse(Handle $handle)
    {
        if (!headers_sent()) {
            header(sprintf('HTTP/1.1 %s', $handle->getStatusCode()));
            foreach ($handle->getHeaders() as $name => $value) {
                header($name.': '.$value, false);
            }
        }

        echo $handle->getContent();
    }
}