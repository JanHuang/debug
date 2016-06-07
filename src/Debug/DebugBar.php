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
use DebugBar\DataCollector\PhpInfoCollector;
use DebugBar\DataCollector\RequestDataCollector;
use DebugBar\DataCollector\TimeDataCollector;
use DebugBar\JavascriptRenderer;
use FastD\Debug\Collectors\DumpCollector;

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
        ConfigCollector::class,
        PDOCollector::class,
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
        $render = $this->getJavascriptRenderer();

        echo $this->wrapOutput($render);
    }
}