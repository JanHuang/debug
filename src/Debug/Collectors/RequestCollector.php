<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/6/6
 * Time: 下午7:19
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Debug\Collectors;

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\RequestDataCollector;

class RequestCollector extends RequestDataCollector implements AssetProvider
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
}