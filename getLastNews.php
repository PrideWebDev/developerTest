#!/usr/local/php/bin/php -q
<?php

use Bitrix\Main\Loader;

$_SERVER["DOCUMENT_ROOT"] = '/home/bitrix/ext_www/site.local/'; //ToDo set bitrix site path
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

$siteID = 's1';  // your site ID - need for language ID

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define("BX_CAT_CRON", true);
define('NO_AGENT_CHECK', true);
if (preg_match('/^[a-z0-9_]{2}$/i', $siteID) === 1)
{
    define('SITE_ID', $siteID);
}
else
{
    die('No defined site - $siteID');
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (!defined('LANGUAGE_ID') || preg_match('/^[a-z]{2}$/i', LANGUAGE_ID) !== 1)
    die('Language id is absent - defined site is bad');

set_time_limit (0);

Loader::includeModule('iblock');
$rss = new CIBlockRSS();
$res = $rss->GetNewsEx('https://lenta.ru', 443, '/rss/news', 'LIMIT=5');
$res = $rss->FormatArray($res);
$res = array_slice($res['item'],0, 5);//don`t work parameter LIMIT for lenta.ru
foreach ($res as $item){
    echo PHP_EOL.$item['title'].PHP_EOL.$item['link'].PHP_EOL.$item['description'].PHP_EOL;
}