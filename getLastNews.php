<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$limit = 5;
$rss = CIBlockRSS::FormatArray(CIBlockRSS::GetNewsEx('lenta.ru', '80', '/rss', ''));
 
$i = 0;
foreach ($rss['item'] as $rssItem) {
    if($i++ > $limit) {
        break;
    }
    $result = '';
    $result .= $rssItem['title'] . PHP_EOL;
    $result .= $rssItem['link'] . PHP_EOL;
    $result .= $rssItem['description'] . PHP_EOL;    
    
    echo nl2br($result);
}