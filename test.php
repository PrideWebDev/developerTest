<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/test/CacheCIBlockElement.php");

$arSelect = ["ID", "NAME", "DATE_ACTIVE_FROM"];

$arFilter = ["IBLOCK_ID" => 1, "ID" => '3', "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"];
if ($res = CacheClasses\CacheCIBlockElement::GetList([], $arFilter, false, ["nPageSize" => 50], $arSelect)) {
    while ($arFields = $res->Fetch()) {
        echo '<pre>' . print_r($arFields, 1) . '</pre>';
    }
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
