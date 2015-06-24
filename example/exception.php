<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/23
 * Time: 上午9:43
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

include __DIR__ . '/../vendor/autoload.php';

//$exception = set_exception_handler('\FastD\Debug\Debug::handle');
//
//restore_exception_handler();

\FastD\Debug\Debugger::enable();

trigger_error('demo');
