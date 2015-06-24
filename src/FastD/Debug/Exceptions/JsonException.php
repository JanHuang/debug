<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/24
 * Time: 上午11:38
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug\Exceptions;

/**
 * Class JsonException
 *
 * @package FastD\Debug\Exceptions
 */
class JsonException extends BaseException
{
    /**
     * @var array
     */
    protected $headers = [
        'Content-Type' => 'application/json; charset=UTF-8;',
    ];

    /**
     * @param array $message
     * @param int   $code
     */
    public function __construct(array $message, $code = 500)
    {
        parent::__construct(json_encode($message, JSON_UNESCAPED_UNICODE), $code);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->getMessage();
    }
}