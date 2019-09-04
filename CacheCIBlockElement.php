<?php

namespace CacheClasses;

class CacheCIBlockElement
{

    /**
     * Кешированный метод получения списка элементов инфоблока
     *
     * @param array $arOrder - поле для сортировки
     * @param array $arFilter - фильтруемое поле
     * @param bool $arGroupBy - Массив полей для группировки элемента
     * @param bool $arNavStartParams - Параметры для постраничной навигации и ограничения количества выводимых элементов
     * @param array $arSelectFields - Массив возвращаемых полей элемента
     * @return object | bool - CIBlockResult или false - если работа с модулем инфоблоков не возможна.
     */

    public static function GetList($arOrder = ["SORT" => "ASC"], $arFilter = [], $arGroupBy = false, $arNavStartParams = false, $arSelectFields = [])
    {
        if (\CModule::IncludeModule("iblock")) {
            $obCache = new \CPHPCache;

            // Время жизни кэша
            $lifeTime = 60 * 10;
            if (!empty($arFilter['IBLOCK_ID'])) {
                $cacheID = $arFilter['IBLOCK_ID'];
            } else {
                $cacheID = "CacheCIBlockElementDefault";
            }

            if ($obCache->InitCache($lifeTime, $cacheID, "/")) {
                $vars = $obCache->GetVars();
                $DbResult = $vars["RESULT_ARRAY"];
            } else {
                $DbResult = \CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);
            }
            if ($obCache->StartDataCache()) {
                $obCache->EndDataCache([
                    "RESULT_ARRAY" => $DbResult,
                ]);
            }
            return $DbResult;
        }
        return false;
    }

}
