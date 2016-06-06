<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/6/6
 * Time: 下午2:16
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Debug\Theme;

use Throwable;

/**
 * Class Theme
 * @package FastD\Debug\Theme
 */
abstract class Theme
{
    /**
     * @var Throwable
     */
    protected $throwable;

    /**
     * @var bool
     */
    protected $cli = false;

    /**
     * @var bool
     */
    protected $display = true;

    /**
     * Theme constructor.
     * @param Throwable $throwable
     * @param bool $display
     */
    public function __construct(Throwable $throwable, $display = true)
    {
        if ('cli' === php_sapi_name()) {
            $this->cli = true;
        }

        $this->throwable = $throwable;

        $this->display = $display;
    }

    /**
     * @return bool
     */
    public function isCli()
    {
        return $this->cli;
    }

    /**
     * @return bool
     */
    public function isDisplay()
    {
        return $this->display;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->throwable->getMessage();
    }

    /**
     * @return array
     */
    public function getHeaders()
    {

    }

    /**
     * @return int|mixed
     */
    public function getStatusCode()
    {

    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->isCli() ? $this->getCli() : $this->getHtml();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return get_class($this->throwable);
    }

    /**
     * @return string
     */
    abstract public function getStyleSheet();

    /**
     * @return string
     */
    abstract public function getTitle();

    /**
     * @return string
     */
    abstract public function getHtml();

    /**
     * @return string
     */
    abstract public function getCli();
}