<?require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

if (\Bitrix\Main\Loader::includeModule("iblock")) {
    $arRes = CIBlockRSS::GetNewsEx("lenta.ru", 80, "/rss/", 'LIMIT=5', false);

    $arRes = CIBlockRSS::FormatArray($arRes, 'N');

    foreach (array_slice( $arRes["item"], 0, 5) as $i => $item)
    {
        echo 'НАЗВАНИЕ: ' . $item['title'] . "<br>" . 'ССЫЛКА: ' . $item['link'] . "<br>" . 'ОПИСАНИЕ: ' .$item['description'] . "<br><br>";
    }

}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
