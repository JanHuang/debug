<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/28
 * Time: 下午9:54
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

include __DIR__ . '/../vendor/autoload.php';

\FastD\Debug\Debug::enable(new \FastD\Logger\Logger(__DIR__ . '/demo.log'));

throw new \FastD\Debug\Exceptions\BaseException('demo');

