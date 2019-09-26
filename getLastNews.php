#!/usr/bin/php
<?php
$news_url = 'https://lenta.ru/rss';
$news_limit = 5;
$content = simplexml_load_file($news_url);
for ($x = 0; $x < $news_limit; $x++) {
	$cur_item = $content->channel->item[$x];
    echo "\t{$cur_item->title}\n\t{$cur_item->link}\n\t".trim($cur_item->description)."\n\n";
}
?>