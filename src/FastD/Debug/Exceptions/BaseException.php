<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/24
 * Time: 上午11:37
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug\Exceptions;

/**
 * Class BaseException
 *
 * @package FastD\Debug\Exceptions
 */
class BaseException extends \Exception
{
    /**
     * @var array
     */
    protected $headers = [
        'Content-Type' => 'text/html; charset=UTF-8'
    ];

    protected $content;

    /**
     * @var array
     */
    protected $context = [];

    /**
     * @param string $message
     * @param int    $statusCode
     * @param array  $headers
     */
    public function __construct($message, $statusCode = 500, array $headers = [])
    {
        parent::__construct($message, $statusCode, null);

        $this->setContent($message);

        $this->setHeaders($headers);
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param array $context
     * @return $this
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return int|mixed
     */
    public function getStatusCode()
    {
        return $this->getCode();
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function addHeaders($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Fetch user custom or system defined content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}