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

$debug = \FastD\Debug\Debug::enable();

class JsonException extends \FastD\Debug\Exceptions\Http\HttpException
{
    public function __construct(array $data)
    {
        parent::__construct(json_encode($data));
    }

    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode()
    {
        return \FastD\Debug\Exceptions\Http\HttpExceptionInterface::HTTP_BAD_REQUEST;
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
}

throw new JsonException(['name' => 'hello world']);