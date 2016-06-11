<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/6/7
 * Time: 下午5:34
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

include __DIR__ . '/../vendor/autoload.php';

$debug = \FastD\Debug\Debug::enable();

$debug->addConfig($debug->getBar(), new FastD\Config\Config());


