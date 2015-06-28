<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/28
 * Time: 下午2:21
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug\Exceptions;

use FastD\Debug\Debug;

class RedirectException extends BaseException
{
    /**
     * Unauthorization
     *
     * @param string $message
     * @param string $url
     * @param int    $code
     * @param int    $timeout
     */
    public function __construct($message, $url, $code = 403, $timeout = 3)
    {
        parent::__construct($message, $code);

        $content = file_get_contents(Debug::$html[302]);

        $content = str_replace([
            '{title}',
            '{content}',
            '{timeout}',
            '{url}'
        ], [
            $this->getMessage(),
            $this->getMessage(),
            $timeout,
            $url,
        ], $content);

        $this->setContent($content);
    }
}