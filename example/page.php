<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/4/21
 * Time: 下午11:10
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

include __DIR__ . '/../vendor/autoload.php';

$debug = \FastD\Debug\Debug::enable(false);

throw new \FastD\Debug\Exceptions\Http\ServerInternalErrorException('500');