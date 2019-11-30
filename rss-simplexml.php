<?php
/**
 * С использованием SimpleXML
 *
**/

/**
 * Данное задание имеет неточную формулировку.
 * Поскольку не поставлены критерии значения "последние" - выводим относительно времени, а не последние из файла rss.
 *
**/
// Если не из коммандной строки или cron - выход
if(!empty($_SERVER["HTTP_HOST"]))
	exit();

// Количество записей
$LIMIT = 5;
// Ссылка RSS
$rssLink = "https://lenta.ru/rss";

// Получаем ленту
$rss = @simplexml_load_file($rssLink);

if($rss):
	// Ппеобразуем объект channel в массив 
	$arrData = (array)$rss->channel;
	// Пробегаем по массиву новостей
	foreach ($arrData['item'] as $key=>$item):
		if($key >= $LIMIT)
			break;
		//echo ($key + 1) . "." . PHP_EOL;
		echo @trim((string)$item->title) . PHP_EOL;
		echo @trim((string)$item->link) . PHP_EOL;
		// очистим описание от html
		echo @trim(@strip_tags((string)$item->description)) . PHP_EOL . PHP_EOL;
		//echo "-----------------------------" . PHP_EOL . PHP_EOL;
	endforeach;
endif;