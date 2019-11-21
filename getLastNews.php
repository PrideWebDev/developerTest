<?php
const FEED_URL = "https://lenta.ru/rss";
const ITEM_LIMIT = 5;

$cnt = 0;

$feed = simplexml_load_file(FEED_URL, 'SimpleXMLElement', LIBXML_NOCDATA);
if ($feed) {
    foreach ($feed->channel->item as $item) {
        if ($cnt++ < ITEM_LIMIT) {
            echo "=========================================". PHP_EOL;
            echo $item->title . PHP_EOL;
            echo $item->link . PHP_EOL;
            echo trim($item->description). PHP_EOL;
        }

    }
    echo "-----------------------------------------". PHP_EOL;
    echo "Have a nice day!";
} else {
    echo "Sorry, something wrong" . PHP_EOL;
    echo "Bay - bay" . PHP_EOL;
}

