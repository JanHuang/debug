<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/6/6
 * Time: 上午2:03
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Debug;

interface ThemeInterface
{
    public function getStyleSheet();

    public function getTitle();

    public function getHtml();

    public function getCli();
}