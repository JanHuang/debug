<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/24
 * Time: 上午11:25
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug;

use FastD\Debug\Exceptions\BaseException;

/**
 * Class ContextHandler
 *
 * @package FastD\Debug
 */
class ContextHandler
{
    private $message;
    private $code;
    private $previous;
    private $trace;
    private $class;
    private $statusCode;
    private $headers = [];
    private $file;
    private $line;
    private $content;
    private $context;

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param mixed $context
     * @return $this
     */
    public function setContext($context)
    {
        if (null === $context) {
            $context = [
                '_GET'     => $_GET,
                '_POST'    => $_POST,
                '_SERVER'  => $_SERVER
            ];
        }
        $this->context = $context;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     * @return $this
     */
    public function setContent($content)
    {
        if (null === $content) {
            $baseException = new BaseException($this->getMessage(), $this->getCode());
            $content = $baseException->getContent();
            unset($baseException);
        }

        $this->content = $content;
        return $this;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param int $line
     * @return $this
     */
    public function setLine($line)
    {
        $this->line = $line;
        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param mixed $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        if (null === $headers) {
            $baseException = new BaseException($this->getMessage(), $this->getCode());
            $headers = $baseException->getHeaders();
            unset($baseException);
        }
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getTrace()
    {
        return $this->trace;
    }

    /**
     * @param array|string $trace
     * @return $this
     */
    public function setTrace($trace)
    {
        $this->trace = $trace;
        return $this;
    }

    /**
     * @return array
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * @param mixed $previous
     * @return $this
     */
    public function setPrevious($previous)
    {
        $this->previous = $previous;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param \Exception $exception
     * @return $this
     */
    public static function create(\Exception $exception)
    {
        $e = new static();

        $e->setContext(method_exists($exception, 'getContext') ? $exception->getContext() : null);
        $e->setMessage($exception->getMessage());
        $e->setCode($exception->getCode());
        $e->setStatusCode(method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : $exception->getCode());
        $e->setHeaders(method_exists($exception, 'getHeaders') ? $exception->getHeaders() : null);
        $e->setClass(get_class($exception));
        $e->setFile($exception->getFile());
        $e->setLine($exception->getLine());
        $e->setContent(method_exists($exception, 'getContent') ? $exception->getContent() : null);
        $e->setTrace(debug_backtrace());

        return $e;
    }
}