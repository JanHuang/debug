<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/6/11
 * Time: 上午11:28
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Debug\Feature;

use DebugBar\DataCollector\PDO\PDOCollector;
use DebugBar\DebugBar;
use FastD\Database\Fdb;
use DebugBar\DataCollector\PDO\TraceablePDO;

/**
 * Class FdbCollector
 *
 * @package FastD\Debug\Feature
 */
trait FdbCollector
{
    /**
     * @param DebugBar|null $debugBar
     * @param Fdb|null $fdb
     * @return $this
     * @throws \DebugBar\DebugBarException
     */
    public function addFdb(DebugBar $debugBar = null, Fdb $fdb = null)
    {
        if (null === $debugBar) {
            return $this;
        }

        $fdb->createPool();

        $collections = new PDOCollector();

        foreach ($fdb as $name => $driverInterface) {
            $traceablePDO = new TraceablePDO($driverInterface->getPdo());
            $collections->addConnection($traceablePDO, $name);
        }

        $debugBar->addCollector($collections);

        return $this;
    }
}