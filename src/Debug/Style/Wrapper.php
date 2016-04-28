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

        $this->style = new StyleSheet($throwable);
    }

    /**
     * @return StyleSheet
     */
    public function getStyleSheet()
    {
        return $this->style;
    }

    /**
     * @return int
     */
    public function send()
    {
        if (!headers_sent() && !$this->style->isCli()) {
            header(sprintf('HTTP/1.1 %s', $this->style->getStatusCode()));
            foreach ($this->style->getHeaders() as $name => $value) {
                header($name . ': ' . $value, false);
            }
        }

        echo $this->style->getContent();

        return 0;
    }
}