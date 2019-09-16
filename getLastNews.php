<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
?>
<?
if(!CModule::IncludeModule("iblock"))
{
    ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
    return;
}
$arRes = CIBlockRSS::GetNewsEx("www.lenta.ru", 80, "/rss/", 'LIMIT=3', false);
$arRes = CIBlockRSS::FormatArray($arRes, 'N');
while(count($arRes["item"])>5){
    array_pop($arRes["item"]);
}
array_walk_recursive($arRes, create_function('&$val, $key', '$val=htmlspecialcharsex($val);'));
//array_walk_recursive($arRes, create_function('&$val, $key', '$val=str_replace(array("    ", "\\r\\n"), array("&nbsp;&nbsp;&nbsp;&nbsp;", "<br>"), HTMLToTxt($val));'));
foreach($arRes["item"] as $i=> $item){
    echo '<p>'.$i.'.'.$item['title'].'<br>'.$item['link'].'<br>'.$item['description'].'</p><br>';
}
?>
