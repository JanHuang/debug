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
 * Class ExceptionHandler
 *
 * @package FastD\Debug
 */
class ExceptionHandler
{
    /**
     * @var Debug
     */
    protected $debug;

    /**
     * @param Debug $debug
     */
    public function __construct(Debug $debug)
    {
        $this->debug = $debug;

        set_exception_handler([$this, 'handle']);
    }

    /**
     * @param \Throwable $throwable
     */
    public function handle(\Throwable $throwable)
    {
        $wrapper = new Wrapper($throwable);
        if (!$this->debug->isDisplay()) {
            $content = false !== ($content = $this->debug->getErrorPage($wrapper->getStatusCode())) ? $content : 'Whool!';
            $wrapper->setHtml($content);
        }
        $this->debug->output($wrapper);
    }

    /**
     * @param Debug $debug
     * @return static
     */
    public static function registerHandle(Debug $debug)
    {
        return new static($debug);
    }
}