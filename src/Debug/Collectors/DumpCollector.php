<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/6/7
 * Time: 下午12:23
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Debug\Collectors;

use DebugBar\DataCollector\MessagesCollector;

class DumpCollector extends MessagesCollector
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'dumper';
    }
}