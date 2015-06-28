<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/28
 * Time: 下午9:55
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

include __DIR__ . '/../vendor/autoload.php';

\FastD\Debug\Debug::enable(null, [
    500 => __DIR__ . '/demo.html',
    404 => __DIR__ . '/demo.html',
]);

throw new \FastD\Debug\Exceptions\BaseException('demo', 404);
