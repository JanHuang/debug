<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/8/8
 * Time: 下午7:32
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

include __DIR__ . '/../vendor/autoload.php';

use FastD\Debug\Debug;

$debug = Debug::enable(false);

class PageException extends \FastD\Debug\Exceptions\HttpException
{
    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode()
    {
        return 400;
    }

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders()
    {
        return [
            'Content-Type' => 'application/json; charset=utf-8'
        ];
    }

    /**
     * Returns response content.
     *
     * @return string
     */
    public function getContent()
    {
        return '{"name": "janhuang"}';
    }
}

throw new PageException();