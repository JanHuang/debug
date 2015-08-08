<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/8/8
 * Time: 下午12:27
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */


include __DIR__ . '/../vendor/autoload.php';

$logger = new \FastD\Logger\Logger();

$debug = \FastD\Debug\Debug::enable(false, $logger->createLogger(__DIR__ . '/demo.log'));

trigger_error('demo error');

