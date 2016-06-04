<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/6/4
 * Time: 上午8:28
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

include __DIR__ . '/../vendor/autoload.php';

\FastD\Debug\Debug::enable();

@include __DIR__ . '/aaa.php';

echo 1;