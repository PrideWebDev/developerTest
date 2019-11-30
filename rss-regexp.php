<?php
/**
 * С использованием регулярных выражений
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

// Получаем ленту и парсим
$content = @file_get_contents($rssLink);
if($content):
	// Извлекаем все новости
	$re = '/<item>(.*)<\/item>/Usi';
	preg_match_all($re, $content, $matches);
	if($matches[0]):
		$re = '/<title>(.*)<\/title>.*<link>(.*)<\/link>.*<description>(.*)<\/description>/si';
		// Пробегаем по массиву новостей
		foreach($matches[0] as $key=>$item):
			if($key >= $LIMIT)
				break;
			// Уберём CDATA
			$item = str_replace(array("<![CDATA[","]]>"),"",$item);
			preg_match($re, $item, $arrData);
			//echo ($key + 1) . "." . PHP_EOL;
			echo @trim($arrData[1]) . PHP_EOL;
			echo @trim($arrData[2]) . PHP_EOL;
			// Очистим описание от html
			echo @trim(@strip_tags($arrData[3])) . PHP_EOL . PHP_EOL;
			//echo "-----------------------------" . PHP_EOL . PHP_EOL;
		endforeach;
	endif;
endif;
