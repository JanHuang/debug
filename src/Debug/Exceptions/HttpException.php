<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/6/6
 * Time: 下午7:00
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Debug\Exceptions;

use Exception;

/**
 * Interface HttpExceptionInterface
 *
 * @package FastD\Debug\Exceptions
 */
abstract class HttpException extends Exception
{
    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    abstract public function getStatusCode();

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    abstract public function getHeaders();

    /**
     * Returns response content.
     *
     * @return string
     */
    abstract public function getContent();
}