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

/**
 * Class ExceptionHandle
 *
 * @package FastD\Debug
 */
class ExceptionHandler
{
    /**
     * @param \Exception $exception
     */
    public function handle(\Exception $exception)
    {
        Debug::output(ContextHandler::create($exception));
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
}