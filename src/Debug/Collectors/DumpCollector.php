<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/6/7
 * Time: 下午12:23
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Debug\Collectors;

class DumpCollector extends Collector
{
    /**
     * Returns an array with the following keys:
     *  - base_path
     *  - base_url
     *  - css: an array of filenames
     *  - js: an array of filenames
     *
     * @return array
     */
    function getAssets()
    {
        // TODO: Implement getAssets() method.
    }

    /**
     * Called by the DebugBar when data needs to be collected
     *
     * @return array Collected data
     */
    function collect()
    {
        // TODO: Implement collect() method.
    }

    /**
     * Returns the unique name of the collector
     *
     * @return string
     */
    function getName()
    {
        return 'dump';
    }

    /**
     * Returns a hash where keys are control names and their values
     * an array of options as defined in {@see DebugBar\JavascriptRenderer::addControl()}
     *
     * @return array
     */
    function getWidgets()
    {
        // TODO: Implement getWidgets() method.
    }
}