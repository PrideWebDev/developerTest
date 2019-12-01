<?php

namespace KDev;

use \Bitrix\Iblock\ElementTable;
use \Bitrix\Main\Data\Cache;

class CachedIBlock {

    private $id;
    private $lifetime;
    private $initdir = false;
    private $basedir = "cache";

    public function __construct($livetime, $id, $initdir = false, $basedir = "cache") {
        $this->lifetime = $livetime;
        $this->id = $id;
    }

//    $query аналогичен запросу \Bitrix\Iblock\ElementTable::getList
//    array(
//        'order' => array('SORT' => 'ASC'), // сортировка
//        'select' => array('ID', 'NAME', 'IBLOCK_ID', 'SORT', 'TAGS'), // выбираемые поля, без свойств. Свойства можно получать на старом ядре \CIBlockElement::getProperty
//        'filter' => array('IBLOCK_ID' => 4), // фильтр только по полям элемента, свойства (PROPERTY) использовать нельзя
//        'group' => array('TAGS'), // группировка по полю, order должен быть пустой
//        'limit' => 1000, // целое число, ограничение выбираемого кол-ва
//        'offset' => 0, // целое число, указывающее номер первого столбца в результате
//        'count_total' => 1 // дает возможность получить кол-во элементов через метод getCount()
//        )
//    )

    public function getList($query) {
        $cache = Cache::createInstance();
        if ($cache->initCache($this->lifetime, $this->id, $initdir, $basedir)) {
            $arResult = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $arResult = array();
            if ($dbItems = ElementTable::getList($query)) {
                $arResult = $dbItems->fetchAll();
            } else {
                $cache->abortDataCache();
            }
            $cache->endDataCache($arResult);
        }
        return $arResult;
    }

}