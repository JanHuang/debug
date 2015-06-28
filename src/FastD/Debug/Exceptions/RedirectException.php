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

class RedirectException extends BaseException
{
    /**
     * @param string $url
     * @param int    $code
     * @param int    $timeout
     */
    public function __construct($url, $code = 500, $timeout = 3)
    {
        parent::__construct('', $code, [
            'Location' => $url,
        ]);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->getMessage();
    }
}