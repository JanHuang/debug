<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/8/8
 * Time: 下午7:32
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

include __DIR__ . '/../vendor/autoload.php';

$debug = \FastD\Debug\Debug::enable(false);

$debug->setCustom(500, __DIR__ . '/demo.json');

throw new \FastD\Debug\Exceptions\HttpException(['nsg' => 'json'], \FastD\Debug\Exceptions\HttpExceptionInterface::HTTP_SERVER_INTERNAL_ERROR, ['Content-Type' => 'application/json; charset=utf-8;']);