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

use DebugBar\DataCollector\ExceptionsCollector;
use DebugBar\DataCollector\MemoryCollector;
use DebugBar\DataCollector\PhpInfoCollector;
use DebugBar\DataCollector\RequestDataCollector;
use DebugBar\DataCollector\TimeDataCollector;
use FastD\Debug\Collectors\DumpCollector;
use DebugBar\JavascriptRenderer;

/**
 * Class DebugBar
 *
 * @package FastD\Debug
 */
class DebugBar extends \DebugBar\DebugBar
{
    /**
     * @var bool
     */
    protected $output = false;

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
     * @return bool
     */
    public function isOutput()
    {
        return $this->output;
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
        $css .= '<style type="text/css">' . $this->getCustomDebugBarStyle() . '</style>';

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
        if ($this->isOutput()) {
            return;
        }
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

        $this->output = true;
    }

    /**
     * @return void
     */
    protected function outputEmpty()
    {
        echo '<body></body>';
    }

    /**
     * @return string
     */
    public function getCustomDebugBarStyle(): string
    {
        return <<<CSS
div.phpdebugbar {
    -webkit-font-smoothing: antialiased;
}
@font-face{
    font-family:'PhpDebugbarFontAwesome';
    src:url('http://libs.useso.com/js/font-awesome/4.2.0/fonts/fontawesome-webfont.eot?v=4.5.0');
    src:url('http://libs.useso.com/js/font-awesome/4.2.0/fonts/fontawesome-webfont.eot?#iefix&v=4.5.0') format('embedded-opentype'),
        url('http://libs.useso.com/js/font-awesome/4.2.0/fonts/fontawesome-webfont.woff?v=4.5.0') format('woff2'),
        url('http://libs.useso.com/js/font-awesome/4.2.0/fonts/fontawesome-webfont.woff?v=4.5.0') format('woff'),
        url('http://libs.useso.com/js/font-awesome/4.2.0/fonts/fontawesome-webfont.ttf?v=4.5.0') format('truetype'),
        url('http://libs.useso.com/js/font-awesome/4.2.0/fonts/fontawesome-webfont.svg?v=4.5.0#fontawesomeregular') format('svg');
}
CSS;
    }
}