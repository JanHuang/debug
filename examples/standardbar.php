<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/6/6
 * Time: 下午5:21
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

include __DIR__ . '/../vendor/autoload.php';

use DebugBar\StandardDebugBar;

$debugbar = new StandardDebugBar();
$debugbarRenderer = $debugbar->getJavascriptRenderer();

$debugbar["messages"]->addMessage("hello world!");
?>
<html>
<head>
    <?php echo $debugbarRenderer->renderHead() ?>
</head>
<body>
...
<?php echo $debugbarRenderer->render() ?>
</body>
</html>