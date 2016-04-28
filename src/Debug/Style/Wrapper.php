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

namespace FastD\Debug\Style;

use FastD\Debug\Exceptions\Exception;
use FastD\Debug\Exceptions\Http\HttpExceptionInterface;

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
     * @var StyleSheet
     */
    private $style;

    /**
     * @param \Throwable $throwable
     */
    public function __construct(\Throwable $throwable)
    {
        $this->exception = $throwable;

        $this->style = new StyleSheet($this);
    }

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
    public function getHeaders()
    {
        return $this->exception->getHeaders();
    }

    /**
     * @return int|mixed
     */
    public function getStatusCode()
    {
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
        $name = $this->getName();

        $trace = ltrim(str_replace('#', '<br />#', $this->exception->getTraceAsString()), '<br />');

        return <<<EOF
<h2 class="block_exception clear_fix">
    <span class="exception_counter">1/1</span>
    <span class="exception_title"><abbr title="{$name}">{$name}</abbr> in <a title="{$this->exception->getFile()} line {$this->exception->getLine()}" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{$filename} line {$this->exception->getLine()}</a>:</span>
    <span class="exception_message">{$this->exception->getMessage()}</span>
</h2>
<div class="block">{$trace}</div>
EOF;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return get_class($this->exception);
    }

    /**
     * @param bool $isCli
     * @return int
     */
    public function send($isCli = false)
    {
        if ($isCli) {
            $path = $this->exception->getFile() . ': ' . $this->exception->getLine();
            $length = strlen($path);

            if ('WIN' === strtoupper(substr(PHP_OS, 0, 3))) {
                echo PHP_EOL;
                echo PHP_EOL;
                echo '[' . $this->getName() . ']' . PHP_EOL;
                echo $this->exception->getMessage();
                echo $path . PHP_EOL;
                echo  PHP_EOL;
                return 0;
            }

            echo PHP_EOL;
            echo chr(27) . '[41m' . str_repeat(' ', $length + 6) . chr(27) . "[0m" . PHP_EOL;
            echo chr(27) . '[41m   ' . '[' . $this->getName() . ']   ' . str_repeat(' ', ($length - strlen($this->getName()) - 2)) . chr(27) . "[0m" . PHP_EOL;
            echo chr(27) . '[41m   ' . $this->exception->getMessage() . str_repeat(' ', $length - strlen($this->exception->getMessage())) . '   ' . chr(27) . "[0m" . PHP_EOL;
            echo chr(27) . '[41m   ' . $path . '   ' . chr(27) . "[0m" . PHP_EOL;
            echo chr(27) . '[41m' . str_repeat(' ', $length + 6) . chr(27) . "[0m" . PHP_EOL;
            echo PHP_EOL;
            return 0;
        }

        if (!headers_sent()) {
            header(sprintf('HTTP/1.1 %s', $this->getStatusCode()));
            foreach ($this->getHeaders() as $name => $value) {
                header($name . ': ' . $value, false);
            }
        }

        if ('' !== ($content = $this->exception->getContent())) {
            echo $content;
        } else {
            echo $this->style->getHtml();
        }

        return 0;
    }
}