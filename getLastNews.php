<?php
$data = simplexml_load_file("https://lenta.ru/rss", 'SimpleXMLElement', LIBXML_NOCDATA);
if ($data) {

    $i = 0;
    while ($i < 5):
        $item = $data->channel->item[$i];
        echo PHP_EOL.$item->title . PHP_EOL.$item->link . PHP_EOL.trim($item->description) . PHP_EOL;
        $i++;
    endwhile;
    echo PHP_EOL;

} else { echo "Parse error" . PHP_EOL; }