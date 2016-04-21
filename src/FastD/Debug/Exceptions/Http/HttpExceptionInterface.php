<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/8/8
 * Time: 上午11:28
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug\Exceptions\Http;

/**
 * Interface HttpExceptionInterface
 *
 * @package FastD\Debug\Exceptions\Http
 */
interface HttpExceptionInterface
{
    const HTTP_SERVER_INTERNAL_ERROR = 500;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_BAD_REQUEST = 400;

    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode();

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders();

    /**
     * Returns response content.
     *
     * @return string
     */
    public function getContent();
}