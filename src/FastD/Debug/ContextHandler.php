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
use FastD\Debug\Exceptions\JsonException;

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

        $getContent = function (\Exception $exception) {
            if ($exception instanceof JsonException) {
                return $exception->getContent();
            }
            $file = isset(Debug::$html[$exception->getCode()]) ? Debug::$html[$exception->getCode()] : false;
            if (file_exists($file)) {
                return file_get_contents($file);
            }
            return $exception->getMessage();
        };
        $message = $getContent($exception);

        $e->setContext(['_GET' => $_GET, '_POST' => $_POST]);
        $e->setMessage($exception->getMessage());
        $e->setCode($exception->getCode());
        $e->setClass(get_class($exception));
        $e->setFile($exception->getFile());
        $e->setLine($exception->getLine());
        $e->setTrace(debug_backtrace());
        $e->setPrevious($exception->getPrevious());
        $e->setStatusCode($exception->getCode());
        $e->setHeaders($exception instanceof BaseException ? $exception->getHeaders() : ['Content-Type: text/html; charset=utf-8;']);
        $e->setContent($message);

        return $e;
    }

    public function __toString()
    {
        return '';
    }
}