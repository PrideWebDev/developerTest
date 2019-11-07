<?
use \Bitrix\Main\Loader;
Loader::includeModule("iblock");
class IBlock
{
    /**
     * @param $select - массив выбираемые поля, пример - array("PREVIEW_PICTURE", "PREVIEW_TEXT", "NAME")
     * @param $filter - массив фильтр, пример - array('IBLOCK_ID' => 1, '=ID' => 1)
     * @param $order - массив порядок сортировки, пример - array('PUBLISH_DATE' => 'DESC', 'TITLE' => 'ASC')
     * @param null $cache_bd - массив кеширование из БД, пример -  array('ttl'=>3600,'cache_joins' => true,) с версии 16.5.9
     * в методе getElement так же реализован управляемый кеш
     * @return array|mixed - возвращаемые значения
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getElement($select, $filter, $order, $cache_bd = null)
    {
        if(empty($cache_bd)){
            $cache_bd = array(
                'ttl' => 3600
            );
        }
        $cacheTtl = intval(36000);
        $cacheID = md5(serialize($filter));
        $cachePath = '/ElementCacheTable';
        $cache = \Bitrix\Main\Application::getInstance()->getManagedCache();
        if ($cache->read($cacheTtl, $cacheID, $cachePath)) {
            $arItems = $cache->get($cacheID);
        }else{
            $res = \Bitrix\Iblock\ElementTable::getList(
                array(
                    'select' => $select,
                    'filter' => $filter,
                    'order'  => $order,
                    "cache"=> $cache_bd
                ));
            $arItems = array();
            while ($arItem = $res->fetch()) {
                $arItems[] = $arItem;
            }
        }
        return $arItems;
    }
}
?>
