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
     * Theme constructor.
     * @param Throwable $throwable
     */
    public function __construct(Throwable $throwable)
    {
        $this->throwable = $throwable;
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
    abstract public function getHtml();

    /**
     * @return string
     */
    public function getCli()
    {
        $content = '';

        $path = $this->throwable->getFile() . ': ' . $this->throwable->getLine();
        $length = strlen($path);

        if ('WIN' === strtoupper(substr(PHP_OS, 0, 3))) {
            $content .= PHP_EOL . PHP_EOL;
            $content .= '[' . $this->getName() . ']' . PHP_EOL;
            $content .= $this->throwable->getMessage();
            $content .= $path . PHP_EOL;
            $content .= PHP_EOL;
            return $content;
        }

        $content .= PHP_EOL;
        $content .= chr(27) . '[41m' . str_repeat(' ', $length + 6) . chr(27) . "[0m" . PHP_EOL;
        $content .= chr(27) . '[41m   ' . '[' . $this->getName() . ']   ' . str_repeat(' ', ($length - strlen($this->getName()) - 2)) . chr(27) . "[0m" . PHP_EOL;
        $content .= chr(27) . '[41m   ' . $this->throwable->getMessage() . str_repeat(' ', $length - strlen($this->throwable->getMessage())) . '   ' . chr(27) . "[0m" . PHP_EOL;
        $content .= chr(27) . '[41m   ' . $path . '   ' . chr(27) . "[0m" . PHP_EOL;
        $content .= chr(27) . '[41m' . str_repeat(' ', $length + 6) . chr(27) . "[0m" . PHP_EOL;
        $content .= PHP_EOL;

        return $content;
    }
}