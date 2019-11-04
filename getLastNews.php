<?php $xml = simplexml_load_file("https://lenta.ru/rss", 'SimpleXMLElement', LIBXML_NOCDATA) or die("Error: Cannot create object"); ?>
<?php for ($i = 0; $i <= 4; $i++) : ?>
<ul>
    <li><?=$xml->channel->item[$i]->title?></li>
    <li><?=$xml->channel->item[$i]->link?></li>
    <li><?=$xml->channel->item[$i]->description?></li>
</ul>
<?php endfor; ?>