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

$logger = new \Monolog\Logger('test');
$stream = new \Monolog\Handler\StreamHandler(__DIR__ . '/test.log');
$stream->setFormatter(new Monolog\Formatter\LineFormatter("[%datetime%] >> %level_name%: >> %message% >> %context% >> %extra%\n"));
$logger->pushHandler($stream);

$debug = \FastD\Debug\Debug::enable(false, $logger);

throw new \FastD\Debug\Exceptions\Http\NotFoundHttpException(404);

