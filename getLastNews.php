#!/usr/bin/env php
<?php
$rss = "https://lenta.ru/rss"; // Задаем адрес $rss ленты
$xml = simplexml_load_file($rss); // Парсим $rss ленту
$result = '';

if($xml){ // Если rss-лента успешно распарсилась
    $result = GetNews($xml); // Получаем новости и записываем в результат
}else{ // Если rss-лента не распарсилась
    $result = "Ошибка: rss лента \"$rss\" недоступна"; // Записываем в результат ошибку
}
echo $result; // Выводим результат

// Получение новостей из xml
function GetNews($xml)  {
    $xml_object = $xml->xpath('//channel/item[position()<6]'); // Получаем последние 5 новостей из xml
    $str = '';
    
    // Перебираем новости и записываем в строку
    foreach($xml_object as $xml_element){
	$str .= $xml_element->title ? '	• ' . trim($xml_element->title) . PHP_EOL : '';
	$str .= $xml_element->link ? '	• ' . trim($xml_element->link) . PHP_EOL : '';
	$str .= $xml_element->description ? '	• ' . trim($xml_element->description) . PHP_EOL : '';
	$str .= PHP_EOL;
    }
    return $str;
}
