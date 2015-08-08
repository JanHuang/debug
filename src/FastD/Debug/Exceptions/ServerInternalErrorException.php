<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/8/8
 * Time: 上午11:39
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug\Exceptions;

class ServerInternalErrorException extends \ErrorException implements HttpExceptionInterface
{
    public function __construct($message, $file = __FILE__, $line = __LINE__)
    {
        parent::__construct($message, HttpExceptionInterface::HTTP_SERVER_INTERNAL_ERROR, 1, $file, $line);
    }
    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode()
    {
        return HttpExceptionInterface::HTTP_SERVER_INTERNAL_ERROR;
    }

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders()
    {
        return [
            'Content-Type' => 'text/html; charset=utf-8'
        ];
    }
}