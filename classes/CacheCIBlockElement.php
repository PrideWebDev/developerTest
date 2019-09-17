<?php
namespace CacheClasses;

class CacheCIBlockElement
{

    public static $lifeTime = 60;

    public static function GetList($params)
    {
        if (\Bitrix\Main\Loader::includeModule("iblock"))
        {

            if(!isset($params['cache']))
            {
                $params['cache'] = array('ttl' => self::$lifeTime,'cache_joins' => true);
            }
            return \Bitrix\Iblock\ElementTable::getList($params);

        }

        return false;

    }
}