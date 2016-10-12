<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/4/21
 * Time: 下午7:18
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug\Tests;

use FastD\Debug\Debug;
use FastD\Debug\Tests\Exceptions\NotFoundException;

class DebugTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Debug::enable();
    }

    /**
     * @expectedException \ErrorException
     * @expectedExceptionMessage Undefined
     */
    public function testError()
    {
        echo $a;
    }
}
