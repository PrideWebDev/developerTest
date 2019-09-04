<?php

$count = 0;
$rss = "https://lenta.ru/rss";
$resXml = @file_get_contents($rss);
if ($resXml === false) die('Error connect to RSS: ' . $rss);
$xml = new SimpleXMLElement($resXml);
if ($xml === false) die('Error parse RSS: ' . $rss);
foreach ($xml->channel->item as $item) {

    if ($item->title) {
        echo 'НАЗВАНИЕ:  ' . $item->title . '<br>' . PHP_EOL;
    }

    if ($item->link) {
        echo 'ССЫЛКА НА НОВОСТЬ:  ' . '<a href="' . $item->link . '">' . $item->link . '</a><br>' . PHP_EOL;
    }

    if ($item->description) {
        echo 'АНОНС:  ' . $item->description . '<br>' . PHP_EOL;
    }

    echo '<hr>';
    $count++;

    if ($count >= 5) {
        break;
    }
}
