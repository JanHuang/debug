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

        $collector = new PDOCollector();

        if ($connectorExists = array_key_exists($collector->getName(), $debugBar->getCollectors())) {
            $collector = $debugBar->getCollector($collector->getName());
        }

        $connections = $collector->getConnections();

        foreach ($fdb as $name => $driverInterface) {
            if (array_key_exists($name, $connections)) {
                continue;
            }
            $traceablePDO = new TraceablePDO($driverInterface->getPdo());
            $collector->addConnection($traceablePDO, $name);
        }

        ! $connectorExists && $debugBar->addCollector($collector);

        return $this;
    }
}