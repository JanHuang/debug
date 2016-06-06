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

namespace FastD\Debug\Theme\Symfony;

use FastD\Debug\Theme\Theme;

/**
 * Symfony 风格 debug 主题。
 *
 * Class StyleSheet
 *
 * @package FastD\Debug\Style
 */
class StyleSheet extends Theme
{
    /**
     * @return string
     */
    public function getHtml()
    {
        $filename = pathinfo($this->throwable->getFile(), PATHINFO_BASENAME);
        $name = $this->getName();

        $trace = ltrim(str_replace('#', '<br />#', $this->throwable->getTraceAsString()), '<br />');

        return <<<EOF
<h2 class="block_exception clear_fix">
    <span class="exception_counter">1/1</span>
    <span class="exception_title"><abbr title="{$name}">{$name}</abbr> in <a title="{$this->throwable->getFile()} line {$this->throwable->getLine()}" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{$filename} line {$this->throwable->getLine()}</a>:</span>
    <span class="exception_message">{$this->throwable->getMessage()}</span>
</h2>
<div class="block">{$trace}</div>
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
#content { width:970px; margin:0 auto; }
            .reset { font: 11px Verdana, Arial, sans-serif; color: #333 }
.reset .clear { clear:both; height:0; font-size:0; line-height:0; }
.reset .clear_fix:after { display:block; height:0; clear:both; visibility:hidden; }
.reset .clear_fix { display:inline-block; }
.reset * html .clear_fix { height:1%; }
.reset .clear_fix { display:block; }
.reset, .reset .block { margin: auto }
.reset abbr { border-bottom: 1px dotted #000; cursor: help; }
.reset p { font-size:14px; line-height:20px; color:#868686; padding-bottom:20px }
.reset strong { font-weight:bold; }
.reset a { color:#6c6159; cursor: default; }
.reset a img { border:none; }
.reset a:hover { text-decoration:underline; }
.reset em { font-style:italic; }
.reset h1, .reset h2 { font: 20px Georgia, "Times New Roman", Times, serif }
.reset .exception_counter { background-color: #fff; color: #333; padding: 6px; float: left; margin-right: 10px; float: left; display: block; }
.reset .exception_title { margin-left: 3em; margin-bottom: 0.7em; display: block; }
.reset .exception_message { margin-left: 3em; display: block; }
.reset .traces li { font-size:12px; padding: 2px 4px; list-style-type:decimal; margin-left:20px; }
.reset .block { background-color:#FFFFFF; padding:10px 28px; margin-bottom:20px;
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
.reset .block_exception { background-color:#ddd; color: #333; padding:20px;
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
.reset a { background:none; color:#868686; text-decoration:none; }
.reset a:hover { background:none; color:#313131; text-decoration:underline; }
.reset ol { padding: 10px 0; }
.reset h1 { background-color:#FFFFFF; padding: 15px 28px; margin-bottom: 20px;
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
    border-radius: 10px;
    border: 1px solid #ccc;
}
EOF;
    }
}