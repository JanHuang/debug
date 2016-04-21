<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/8/25
 * Time: 下午10:44
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

include __DIR__ . '/../vendor/autoload.php';

\FastD\Debug\Debug::enable();

throw new \FastD\Debug\Exceptions\Http\NotFoundHttpException(404);