<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/4/21
 * Time: 下午10:17
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug\Exceptions;

/**
 * Class Exception
 *
 * @package FastD\Debug\Exceptions
 */
class Exception extends \ErrorException
{
    /**
     * @param $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @param $line
     * @return $this
     */
    public function setLine($line)
    {
        $this->line = $line;

        return $this;
    }
}