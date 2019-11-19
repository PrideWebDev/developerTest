<?php
/**
 * Created by PhpStorm.
 * User: Ryumin_DS
 * Date: 19.11.2019
 * Time: 11:57
 */
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use \Bitrix\Main\Loader;
Loader::includeModule("iblock");

Class IblockWork {
    function GetElementsListCache($ListCacheParams){ // Метод - Получение кэшированногого списка элементов инфоблока
        $cacheTime = 3600; // Время действия кэша
        $cacheId = md5(serialize($ListCacheParams)); // id кэша, собранный из параметров, переданных в метод
        $cachePath = 'get_elements_list'; // директория кэша
        $obCache = new CPHPCache();
        if ($obCache->InitCache($cacheTime, $cacheId, $cachePath)){ // Если кэш с текущими параметрами существует
            $elements_list = $obCache->GetVars();
        }else{ // Если кэш с текущими параметрами Не существует
            $elements_list = CIBlockElement::GetList($ListCacheParams['arSort'], $ListCacheParams['arSelect'], false, Array("nPageSize"=>50), $ListCacheParams['arFilter']); // Получаем список элементов инфоблока
            $obCache->StartDataCache($cacheTime, $cacheId, $cachePath);
            $obCache->EndDataCache($elements_list); // Записываем список в кэш
        }
        return $elements_list;
    }
}
/* END Класс - Работа с инфоблоками */
/* Применение метода - получение кэшированногого списка элементов инфоблока */
$ListCacheParams = Array( // Задаем параметры метода
    'arSort' => Array("ID" => 'asc'), // Сортировка
    'arSelect' => Array("ID", "NAME", "DATE_ACTIVE_FROM"), // Поля
    'arFilter' => Array("IBLOCK_ID"=>IntVal(24), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y") // Фильтр
);
$object = new IblockWork;
$elements_list = $object->GetElementsListCache($ListCacheParams); // Обращаемся к методу
echo '<pre>';
print_r($elements_list); // Выводим результат
echo '</pre>';
/* END Применение метода - получение кэшированногого списка элементов инфоблока */
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>