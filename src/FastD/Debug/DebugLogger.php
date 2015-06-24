<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/23
 * Time: 下午11:35
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug;

use FastD\Logger\Logger;

class DebugLogger
{
    protected $logger;

    public function __construct(array $config)
    {
        $this->logger = new Logger($config);
    }

    public function save($content)
    {

    }
}