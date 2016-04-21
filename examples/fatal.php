<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/8/14
 * Time: 下午2:10
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

include __DIR__ . '/../vendor/autoload.php';

$debug = \FastD\Debug\Debug::enable();

try {
    echo abc();
} catch (Exception $e) {
    echo $e->getMessage();
}
