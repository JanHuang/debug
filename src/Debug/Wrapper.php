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
    private $throwable;

    /**
     * @var StyleSheet
     */
    protected $style;

    /**
     * @param \Throwable $throwable
     * @param bool $display
     */
    public function __construct(\Throwable $throwable, $display = true)
    {
        $this->throwable = $throwable;

        $this->style = new StyleSheet($throwable, $display);
    }

    /**
     * @return \Exception|HttpExceptionInterface|\Throwable
     */
    public function getThrowable()
    {
        return $this->throwable;
    }

    /**
     * @return StyleSheet
     */
    public function getStyleSheet()
    {
        return $this->style;
    }

    /**
     * @param int
     * @return int
     */
    protected function filterStatusCode($statusCode)
    {
        return ($statusCode < 100 || $statusCode > 505) ? 500 : $statusCode;
    }

    /**
     * @return int
     */
    public function send()
    {
        if (!headers_sent() && !$this->style->isCli()) {
            header(sprintf('HTTP/1.1 %s', $this->filterStatusCode($this->style->getStatusCode())));
            foreach ($this->style->getHeaders() as $name => $value) {
                header($name . ': ' . $value, false);
            }
        }

        echo $this->style->getContent();

        return 0;
    }

    public static function output(\Throwable $throwable)
    {
        echo $throwable->getMessage();
    }
}