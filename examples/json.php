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

use FastD\Debug\Exceptions\HttpException;

include __DIR__ . '/../vendor/autoload.php';

$debug = \FastD\Debug\Debug::enable();

class JsonException extends HttpException
{
    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode()
    {
        return '400';
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
        // TODO: Implement getContent() method.
    }

    /**
     * Construct the exception. Note: The message is NOT binary safe.
     * @link http://php.net/manual/en/exception.construct.php
     * @param string $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @param Exception $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
     * @since 5.1.0
     */
    public function __construct($message, $code = 400)
    {
        parent::__construct(json_encode($message), $code);
    }
}

throw new JsonException(['name' => 'hello world']);