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
use FastD\Debug\Exceptions\HttpExceptionInterface;

/**
 * Class Wrapper
 *
 * @package FastD\Debug
 */
class Wrapper
{
    /**
     * @var \Exception|HttpExceptionInterface
     */
    private $exception;

    /**
     * @var Style
     */
    private $style;

    /**
     * @var bool
     */
    private $isJson;

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->exception->getMessage();
    }

    /**
     * @return array
     */
    public function getTrace()
    {
        return $this->exception->getTrace();
    }

    /**
     * @return string
     */
    public function getTraceAsString()
    {
        return $this->exception->getTraceAsString();
    }

    /**
     * @return \Exception
     */
    public function getPrevious()
    {
        return $this->exception->getPrevious();
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->exception->getFile();
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->exception->getLine();
    }

    /**
     * @param \Exception $exception
     */
    public function __construct($exception)
    {
        $this->exception = $exception;
        $this->style = new Style($this);
    }

    /**
     * @return boolean
     */
    public function isApplicationJson()
    {
        if (null === $this->isJson) {
            if ($this->exception instanceof HttpExceptionInterface) {
                $this->isJson = false === strpos($this->exception->getHeaders()['Content-Type'], 'application/json') ? false : true;
            }
        }

        return $this->isJson;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        if ($this->exception instanceof HttpExceptionInterface) {
            return $this->exception->getHeaders();
        }

        return [
            'Content-Type' => 'text/html; charset=utf-8;',
        ];
    }

    /**
     * @return int|mixed
     */
    public function getStatusCode()
    {
        if ($this->exception instanceof HttpExceptionInterface) {
            return $this->exception->getStatusCode();
        }

        return $this->exception->getCode();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        switch ($this->exception->getCode()) {
            case 404:
                $title = 'Sorry, the page you are looking for could not be found.';
                break;
            default:
                $title = 'Whoops, looks like something went wrong.';
        }

        return $title;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        $filename = pathinfo($this->exception->getFile(), PATHINFO_BASENAME);
        $classname = get_class($this->exception);

        $trace = ltrim(str_replace('#', '<br />#', $this->exception->getTraceAsString()), '<br />');

        return <<<EOF
<h2 class="block_exception clear_fix">
    <span class="exception_counter">1/1</span>
    <span class="exception_title"><abbr title="{$classname}">{$classname}</abbr> in <a title="{$this->exception->getFile()} line {$this->exception->getLine()}" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{$filename} line {$this->exception->getLine()}</a>:</span>
    <span class="exception_message">{$this->exception->getMessage()}</span>
</h2>
<div class="block">{$trace}</div>
EOF;
    }

    /**
     * @param $html
     * @return $this
     */
    public function custom($html)
    {
        $this->style->setHtml($html);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->style->getHtml();
    }

    public function getName()
    {
        return get_class($this->exception);
    }
}