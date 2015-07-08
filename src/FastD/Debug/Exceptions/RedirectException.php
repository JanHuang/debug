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
     * @param int    $timeout
     * @param int    $code
     */
    public function __construct($message, $url, $timeout = 3, $code = 403)
    {
        $content = file_get_contents(Debug::$html[302]);

        $content = str_replace([
            '{title}',
            '{content}',
            '{timeout}',
            '{url}'
        ], [
            $message,
            $message,
            $timeout,
            $url,
        ], $content);

        $this->setContent($content);

        parent::__construct($content, $code);
    }
}