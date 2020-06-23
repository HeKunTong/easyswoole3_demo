<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19-11-6
 * Time: 上午10:31
 */


require_once "vendor/autoload.php";
require_once "App/Utility/simple_dom_html.php";

$url = 'https://list.jd.com/list.html';
$params = [
    'cat' => '9987,653,655',
    'ev' => 'exbrand_Apple^',
    'cid3' => '655',
];
$url = $url.'?'.http_build_query($params);
$content = shell_exec("phantomjs template.js $url");
file_put_contents('a.html', $content);
$html = new simple_html_dom();
$html->load($content);
$curr = $html->find('.p-num a.curr', 0);
$skip = $html->find('.p-skip b', 0);
var_dump($curr->plaintext);
var_dump($skip->plaintext);
