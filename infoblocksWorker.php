<?php
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
\Bitrix\Main\Loader::includeModule('iblock');

class infoblocksWorker
{
    public function getResult(
    	$arSelect = array(),
    	$arFilter = array(),
    	$arSort = array()
    	) {
			return \Bitrix\Iblock\ElementTable::getList(array('select' => $arSelect, 'filter' => $arFilter, 'order' => $arSort, 'cache' => array('ttl' => 3600)));
			}
}

//(new infoblocksWorker)->getResult(array('ID'), array('=IBLOCK_ID' => 5), array('ID' => 'DESC'));
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>