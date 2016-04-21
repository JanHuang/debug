<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/4/21
 * Time: 下午9:26
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug\Tests;

use FastD\Debug\Handler\ErrorHandler;

class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testError()
    {
        ErrorHandler::register();

        v();
    }
}
