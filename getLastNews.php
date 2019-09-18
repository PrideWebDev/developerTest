#!/usr/bin/php
<?php
$document = new DOMDocument();
$document->load('https://lenta.ru/rss');
$items = $document->getElementsByTagName('item');

for($i = 0; $i < $items->length && $i < 5; $i++) 
{
    echo '* ' . $items[$i]->getElementsByTagName('title')[0]->nodeValue . PHP_EOL .
         '* ' . $items[$i]->getElementsByTagName('link')[0]->nodeValue . PHP_EOL . 
         '* ' . $items[$i]->getElementsByTagName('description')[0]->textContent . PHP_EOL . PHP_EOL;
}
