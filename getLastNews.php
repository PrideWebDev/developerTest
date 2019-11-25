<?php
$_SERVER["DOCUMENT_ROOT"] = '/var/www/developerTest/';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);

Loader::includeModule('iblock');
$obCIBlockRss = new CIBlockRSS();
$resNews = $obCIBlockRss->GetNewsEx('https://lenta.ru', 443, '/rss/news/', '');
$arNews = $obCIBlockRss->FormatArray($resNews)['item'];
$arNews = array_slice($arNews, 0, 5);
foreach ($arNews as $arNew) {
    echo PHP_EOL . $arNew['title'] . PHP_EOL . $arNew['link'] . PHP_EOL . $arNew['description'] . PHP_EOL;
}