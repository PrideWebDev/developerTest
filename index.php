<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require($_SERVER["DOCUMENT_ROOT"]."/test/classes/CacheCIBlockElement.php");


$params = array(
    'select' => array("ID", "NAME", "PREVIEW_TEXT"),
    'filter' => array("IBLOCK_ID" => 5, "ACTIVE" => "Y"),
    'group' => array(),
    'order'=> array('ID' => 'ASC'),
    'limit'=>  10
);


if ($res = CacheClasses\CacheCIBlockElement::GetList($params))
{
    while ($arFields = $res->Fetch()) {
        echo '<pre>' . print_r($arFields, 1) . '</pre>';
    }
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");