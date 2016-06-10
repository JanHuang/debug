<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/6/7
 * Time: 上午9:57
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Debug;

use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DataCollector\ExceptionsCollector;
use DebugBar\DataCollector\MemoryCollector;
use DebugBar\DataCollector\PDO\PDOCollector;
use DebugBar\DataCollector\PDO\TraceablePDO;
use DebugBar\DataCollector\PhpInfoCollector;
use DebugBar\DataCollector\RequestDataCollector;
use DebugBar\DataCollector\TimeDataCollector;
use FastD\Debug\Collectors\DumpCollector;
use DebugBar\JavascriptRenderer;
use FastD\Database\Fdb;

/**
 * Class DebugBar
 *
 * @package FastD\Debug
 */
class DebugBar extends \DebugBar\DebugBar
{
    const PRESET_COLLECTORS = [
        PhpInfoCollector::class,
        DumpCollector::class,
        RequestDataCollector::class,
        TimeDataCollector::class,
        ExceptionsCollector::class,
        MemoryCollector::class,
    ];
    
    /**
     * DebugBar constructor.
     *
     * @param array $collectors
     */
    public function __construct(array $collectors = DebugBar::PRESET_COLLECTORS)
    {
        foreach ($collectors as $collector) {
            $this->addCollector(new $collector);
        }
    }

    /**
     * @param Fdb $fdb
     * @return $this
     * @throws \DebugBar\DebugBarException
     */
    public function addFdb(Fdb $fdb)
    {
        $fdb->createPool();

        $collections = new PDOCollector();

        foreach ($fdb as $name => $driverInterface) {
            $traceablePDO = new TraceablePDO($driverInterface->getPdo());
            $collections->addConnection($traceablePDO, $name);
        }

        $this->addCollector($collections);
        
        return $this;
    }

    /**
     * @param array $config
     * @return $this
     * @throws \DebugBar\DebugBarException
     */
    public function addConfig(array $config)
    {
        $this->addCollector(new ConfigCollector($config));

        return $this;
    }
    
    /**
     * @param JavascriptRenderer $renderer
     * @return string
     */
    public function wrapOutput(JavascriptRenderer $renderer)
    {
        ob_start();

        $renderer->dumpCssAssets();

        $css = '<style type="text/css">' . ob_get_contents() . '</style>';

        ob_clean();

        $renderer->dumpJsAssets();

        $js = '<script type="text/javascript">' . ob_get_contents() . '</script>';

        ob_end_clean();

        return $css . $js . $renderer->render();
    }

    /**
     * @return void
     */
    public function output()
    {
        // It not application/{type} header. To output bar information.
        $isApplication = (function () {
            $list = headers_list();
            foreach ($list as $value) {
                list($name, $value) = explode(':', $value);
                if (strtolower($name) == 'content-type' && (false !== strpos(trim($value), 'application'))) {
                    return true;
                }
            }
            return false;
        })();

        if (!headers_sent() && 'cli' !== PHP_SAPI && !$isApplication) {
            $this->outputEmpty();
        }

        if (!$isApplication) {
            $render = $this->getJavascriptRenderer();
            echo $this->wrapOutput($render);
        }
    }

    /**
     *
     */
    protected function outputEmpty()
    {
        echo '<body></body>';
    }
}