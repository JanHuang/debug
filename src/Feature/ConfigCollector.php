<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/6/11
 * Time: 上午11:28
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Debug\Feature;

use FastD\Config\Config;
use DebugBar\DebugBar;

/**
 * Class ConfigCollector
 *
 * @package FastD\Debug\Feature
 */
trait ConfigCollector
{
    /**
     * @param DebugBar|null $debugBar
     * @param Config|null $config
     * @return $this
     */
    public function addConfig(DebugBar $debugBar = null, Config $config = null)
    {
        if (null === $debugBar) {
            return $this;
        }
        
        $debugBar->addCollector(new \DebugBar\DataCollector\ConfigCollector($config->all()));

        return $this;
    }
}