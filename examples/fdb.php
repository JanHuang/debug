<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/6/7
 * Time: 下午4:45
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

include __DIR__ . '/../vendor/autoload.php';

$debug = \FastD\Debug\Debug::enable();

$fdb = new \FastD\Database\Fdb([
    'write' => [
        'database_type'     => 'mysql',
        'database_host'     => '127.0.0.1',
        'database_port'     => 3306,
        'database_user'     => 'root',
        'database_pwd'      => '123456',
        'database_charset'  => 'utf8',
        'database_name'     => 'test',
        'database_prefix'   => ''
    ],
    'read' => [
        'database_type'     => 'mysql',
        'database_host'     => '127.0.0.1',
        'database_port'     => 3306,
        'database_user'     => 'root',
        'database_pwd'      => '123456',
        'database_charset'  => 'utf8',
        'database_name'     => 'test',
        'database_prefix'   => ''
    ],
]);

$debug->addFdb($debug->getBar(), $fdb);
$debug->addFdb($debug->getBar(), $fdb);

$fdb->getDriver('read')->query('select * from test;')->execute()->getAll();

