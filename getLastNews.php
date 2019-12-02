<?php
/**
 * File: xmlPWD.php
 * Date: 02/12/2019 / 18:07
 */

$xml = simplexml_load_file("https://lenta.ru/rss", 'SimpleXMLElement', LIBXML_NOCDATA);
if ($xml) {
    $i = 0;
    while ($i < 5):
        $item = $xml->channel->item[$i];
        echo "\n".$item->title . "\n".$item->link . "\n".trim($item->description) . "\n";
        $i++;
    endwhile;

} else { echo "Parse error"; }
