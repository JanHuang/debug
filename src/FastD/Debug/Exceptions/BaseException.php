<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/24
 * Time: 上午11:37
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Debug\Exceptions;

/**
 * Class BaseException
 *
 * @package FastD\Debug\Exceptions
 */
class BaseException extends \Exception
{
    /**
     * @var array
     */
    protected $headers = [
        'Content-Type' => 'text/html; charset=UTF-8'
    ];

    protected $context = [];

    public function __construct($message, $statusCode = 500, array $headers = [])
    {
        parent::__construct($message, $statusCode, null);
        $this->context = [
            '_GET'     => $_GET,
            '_POST'    => $_POST,
            '_SERVER'  => $_SERVER,
        ];
        $this->setHeaders($headers);
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param array $context
     * @return $this
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    public function getStatusCode()
    {
        return $this->getCode();
    }

    public function addHeaders($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Fetch user custom or system defined content.
     *
     * @return string
     */
    public function getContent()
    {
        $class = get_class($this);

        $filename = pathinfo($this->getFile(), PATHINFO_FILENAME) . '.' . pathinfo($this->getFile(), PATHINFO_EXTENSION);

        $context = $this->getContext();

        $get = '[<br />';

        foreach ($context['_GET'] as $name => $value) {
            $get .= $name . ' => ' . $value . '<br />';
        }
        $get .= ']';
        $post = '[<br />';
        foreach ($context['_POST'] as $name => $value) {
            $post .= $name . ' => ' . $value . '<br />';
        }
        $post .= ']';
        $server = '[<br />';
        foreach ($context['_SERVER'] as $name => $value) {
            $server .= $name . ' => ' . $value . '<br />';
        }
        $server .= ']';
        return <<<EOF
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="robots" content="noindex,nofollow" />
        <title>{$this->getMessage()}</title>
        <style>
            html{color:#000;background:#FFF;}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}q:before,q:after{content:'';}abbr,acronym{border:0;font-variant:normal;}sup{vertical-align:text-top;}sub{vertical-align:text-bottom;}input,textarea,select{font-family:inherit;font-size:inherit;font-weight:inherit;}input,textarea,select{*font-size:100%;}legend{color:#000;}
            html { background: #eee; padding: 10px }
            img { border: 0; }
            .content { width:960px; margin:0 auto; overflow: auto;}
            .message, .backtrace {margin: 20px 0; background-color: #fff; padding: 20px ; font-size:18px; line-height: 24px;}
            .message storage {font-size: 24px; margin-right: 15px;}
            .file storage{ font-size:18px; color: #f15923; font-weight: bold;}
        </style>
    </head>
    <body>
        <section class="content">
            <div class="message">
                <p><storage>Message:</storage> {$this->getMessage()}</p>
                <p><storage style="font-size:18px; color:#f15923; ">{$class}</storage> in <storage style="color:#f15923;">{$filename}</storage> line to: <storage style="color: red;">{$this->getLine()}</storage></p>
                <p>Path: <storage style="font-size:16px; color: red; cursor: pointer; text-decoration: underline;">{$this->getFile()}</storage></p>
            </div>
            <div class="backtrace">
                <div class="file">

                </div>
                <div style="font-size:12px;">
                    <pre style="margin-top:20px;">
----get----
{$get}
----post----
{$post}
----server----
{$server}
----trace----
<p>{$this->getTraceAsString()}</p>
                    </pre>
                </div>
            </div>
        </section>
    </body>
</html>
EOF;
    }
}