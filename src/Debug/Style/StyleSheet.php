<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/8/8
 * Time: 上午10:57
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug\Style;

use FastD\Debug\Exceptions\Http\HttpException;

/**
 * Class StyleSheet
 *
 * @package FastD\Debug\Style
 */
class StyleSheet
{
    /**
     * @var \Throwable
     */
    protected $throwable;

    /**
     * @var bool
     */
    protected $cli = false;

    /**
     * @var bool
     */
    protected $display = true;

    /**
     * StyleSheet constructor.
     * @param \Throwable $throwable
     * @param bool $display
     */
    public function __construct(\Throwable $throwable, $display = true)
    {
        $this->throwable = $throwable;

        if ('cli' === php_sapi_name()) {
            $this->cli = true;
        }

        $this->display = $display;
    }

    /**
     * @return bool
     */
    public function isCli()
    {
        return $this->cli;
    }

    /**
     * @return bool
     */
    public function isDisplay()
    {
        return $this->display;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->throwable->getMessage();
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        if ($this->throwable instanceof HttpException) {
            return $this->throwable->getHeaders();
        }

        return [
            'Content-Type' => 'text/html; charset=utf-8;'
        ];
    }

    /**
     * @return int|mixed
     */
    public function getStatusCode()
    {
        if ($this->throwable instanceof HttpException) {
            return $this->throwable->getStatusCode();
        }

        return $this->throwable->getCode();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        switch ($this->getStatusCode()) {
            case 404:
                $title = 'Sorry, the page you are looking for could not be found.';
                break;
            default:
                $title = 'Whoops, looks like something went wrong.';
        }

        return $title;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        if ($this->isCli()) {
            return $this->getCliContent();
        }

        if (method_exists($this->throwable, 'getContent') && !empty($content = $this->throwable->getContent())) {
            return $content;
        }

        return $this->getHtml();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return get_class($this->throwable);
    }

    /**
     * @return string
     */
    public function getCliContent()
    {
        $content = '';

        $path = $this->throwable->getFile() . ': ' . $this->throwable->getLine();
        $length = strlen($path);

        if ('WIN' === strtoupper(substr(PHP_OS, 0, 3))) {
            $content .= PHP_EOL . PHP_EOL;
            $content .= '[' . $this->getName() . ']' . PHP_EOL;
            $content .= $this->throwable->getMessage();
            $content .= $path . PHP_EOL;
            $content .= PHP_EOL;
            return $content;
        }

        $content .= PHP_EOL;
        $content .= chr(27) . '[41m' . str_repeat(' ', $length + 6) . chr(27) . "[0m" . PHP_EOL;
        $content .= chr(27) . '[41m   ' . '[' . $this->getName() . ']   ' . str_repeat(' ', ($length - strlen($this->getName()) - 2)) . chr(27) . "[0m" . PHP_EOL;
        $content .= chr(27) . '[41m   ' . $this->throwable->getMessage() . str_repeat(' ', $length - strlen($this->throwable->getMessage())) . '   ' . chr(27) . "[0m" . PHP_EOL;
        $content .= chr(27) . '[41m   ' . $path . '   ' . chr(27) . "[0m" . PHP_EOL;
        $content .= chr(27) . '[41m' . str_repeat(' ', $length + 6) . chr(27) . "[0m" . PHP_EOL;
        $content .= PHP_EOL;

        return $content;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        $title = $this->getTitle();
        $style = $this->getStyleSheet();
        $filename = pathinfo($this->throwable->getFile(), PATHINFO_BASENAME);
        $name = $this->getName();

        $trace = ltrim(str_replace('#', '<br />#', $this->throwable->getTraceAsString()), '<br />');

        $content = '';

        if ($this->isDisplay()) {
            $content = <<<EOF
<h2 class="block_exception clear_fix">
    <span class="exception_counter">1/1</span>
    <span class="exception_title"><abbr title="{$name}">{$name}</abbr> in <a title="{$this->throwable->getFile()} line {$this->throwable->getLine()}" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{$filename} line {$this->throwable->getLine()}</a>:</span>
    <span class="exception_message">{$this->throwable->getMessage()}</span>
</h2>
<div class="block">{$trace}</div>
EOF;
        }

        if (isset($this->getHeaders()['Content-Type'])) {
            if (false !== strpos($this->getHeaders()['Content-Type'], 'application/json')) {
                return $this->getMessage();
            }
        }
        return <<<EOF
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>{$title}</title>
        <meta name="robots" content="noindex,nofollow" />
        <style>{$style}</style>
    </head>
    <body>
    <div id="sf-resetcontent" class="sf-reset">
    <h1>{$title}</h1>
    {$content}
    </div>
    </body>
</html>
EOF;
    }

    /**
     * @return string
     */
    public function getStyleSheet()
    {
        return <<<EOF

html{color:#000;background:#FFF;}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}q:before,q:after{content:'';}abbr,acronym{border:0;font-variant:normal;}sup{vertical-align:text-top;}sub{vertical-align:text-bottom;}input,textarea,select{font-family:inherit;font-size:inherit;font-weight:inherit;}input,textarea,select{*font-size:100%;}legend{color:#000;}

html { background: #eee; padding: 10px }
img { border: 0; }
#sf-resetcontent { width:970px; margin:0 auto; }
            .sf-reset { font: 11px Verdana, Arial, sans-serif; color: #333 }
.sf-reset .clear { clear:both; height:0; font-size:0; line-height:0; }
.sf-reset .clear_fix:after { display:block; height:0; clear:both; visibility:hidden; }
.sf-reset .clear_fix { display:inline-block; }
.sf-reset * html .clear_fix { height:1%; }
.sf-reset .clear_fix { display:block; }
.sf-reset, .sf-reset .block { margin: auto }
.sf-reset abbr { border-bottom: 1px dotted #000; cursor: help; }
.sf-reset p { font-size:14px; line-height:20px; color:#868686; padding-bottom:20px }
.sf-reset strong { font-weight:bold; }
.sf-reset a { color:#6c6159; cursor: default; }
.sf-reset a img { border:none; }
.sf-reset a:hover { text-decoration:underline; }
.sf-reset em { font-style:italic; }
.sf-reset h1, .sf-reset h2 { font: 20px Georgia, "Times New Roman", Times, serif }
.sf-reset .exception_counter { background-color: #fff; color: #333; padding: 6px; float: left; margin-right: 10px; float: left; display: block; }
.sf-reset .exception_title { margin-left: 3em; margin-bottom: 0.7em; display: block; }
.sf-reset .exception_message { margin-left: 3em; display: block; }
.sf-reset .traces li { font-size:12px; padding: 2px 4px; list-style-type:decimal; margin-left:20px; }
.sf-reset .block { background-color:#FFFFFF; padding:10px 28px; margin-bottom:20px;
    -webkit-border-bottom-right-radius: 16px;
    -webkit-border-bottom-left-radius: 16px;
    -moz-border-radius-bottomright: 16px;
    -moz-border-radius-bottomleft: 16px;
    border-bottom-right-radius: 16px;
    border-bottom-left-radius: 16px;
    border-bottom:1px solid #ccc;
    border-right:1px solid #ccc;
    border-left:1px solid #ccc;
}
.sf-reset .block_exception { background-color:#ddd; color: #333; padding:20px;
    -webkit-border-top-left-radius: 16px;
    -webkit-border-top-right-radius: 16px;
    -moz-border-radius-topleft: 16px;
    -moz-border-radius-topright: 16px;
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
    border-top:1px solid #ccc;
    border-right:1px solid #ccc;
    border-left:1px solid #ccc;
    overflow: hidden;
    word-wrap: break-word;
}
.sf-reset a { background:none; color:#868686; text-decoration:none; }
.sf-reset a:hover { background:none; color:#313131; text-decoration:underline; }
.sf-reset ol { padding: 10px 0; }
.sf-reset h1 { background-color:#FFFFFF; padding: 15px 28px; margin-bottom: 20px;
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
    border-radius: 10px;
    border: 1px solid #ccc;
}
EOF;
    }
}