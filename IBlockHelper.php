<?php

    class IBlockHelper {

        public function getCachedList($arCacheParams = array(), $arParams = array()) {

            if (empty($arParams)) {
                return false;
            }
            $arResult = array();
            $cacheID = isset($arCacheParams["cacheID"] ? $arCacheParams["cacheID"] : md5(serialize($arParams));
            $cacheTime = isset($arCacheParams["cacheTime"]) ? $arCacheParams["cacheTime"] : 36000;
            $cachePath = isset($arCacheParams["cachePath"]) ? $arCacheParams["cachePath"] : "/".$cacheID;
            $cache = new CPHPCache();

            if ($cacheTime > 0 && $cache->InitCache($cacheTime, $cacheID, $cachePath)) {
                $arResult = $cache->GetVars();
            }

            if (empty($arResult) && CModule::IncludeModule('iblock')) {

                $arOrder = isset($arParams["arOrder"]) ? $arParams["arOrder"] : array("SORT" => "ASC");
                $arFilter = isset($arParams["arFilter"]) ? $arParams["arFilter"] : array();
                $arNavStartParams = isset($arParams["arNavStartParams"]) ? $arParams["arNavStartParams"] : false;
                $arSelectFields = isset($arParams["arSelectFields"]) ? $arParams["arSelectFields"] : array();

                $res = CIBlockElement::getList($arOrder, $arFilter, false, $arNavStartParams, $arSelectFields);

                while ($ob = $res->GetNextElement()) {
                    $arResult[] = $ob->GetFields();
                }

                if ($cacheTime > 0) {
                    $cache->StartDataCache($cacheTime, $cacheID, $cachePath);
                    $cache->EndDataCache($arResult);
                }

            }

            return $arResult;

        }

    }

?>