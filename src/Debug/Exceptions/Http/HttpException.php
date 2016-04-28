<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/8/8
 * Time: 下午7:07
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug\Exceptions\Http;

use FastD\Debug\Exceptions\Exception;

/**
 * Class HttpException
 *
 * @package FastD\Debug\Exceptions
 */
class HttpException extends Exception implements HttpExceptionInterface
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var array
     */
    protected $headers = [
        'Content-Type' => 'text/html; charset=utf-8;'
    ];

    /**
     * @param string $message
     * @param int    $code
     * @param array  $headers
     */
    public function __construct($message, $code = HttpExceptionInterface::HTTP_SERVER_INTERNAL_ERROR, array $headers = [])
    {
        $this->statusCode = $code;
        $this->headers = array_merge($this->headers, $headers);
        parent::__construct($message, $code, 1);
    }

    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Returns response content.
     *
     * @return string
     */
    public function getContent()
    {
        return '';
    }
}