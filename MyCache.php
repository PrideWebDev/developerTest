<?php

namespace MyClasses;

use \Bitrix\Iblock\ElementTable;
use \Bitrix\Main\Data\Cache;

/**
 * Class MyCache
 * @package MyClass
 */
class MyCache {

    /**
     * @var integer Время жизни кеша в секундах
     */
    private $ttl;

    /**
     * @var string Уникальный идентификатор кеша
     */
    private $uniqueString;

    /**
     * @var bool Папка, в которой хранится кеш, относительно /bitrix/cache/
     */
    private $initDir = false;

    /**
     * @var string Базовая директория кеша. По умолчанию равен cache
     */
    private $baseDir = "cache";

    /**
     * MyCache constructor.
     * @param integer $ttl Время жизни кеша в секундах
     * @param string $uniqueString Уникальный идентификатор кеша
     * @param bool $initDir Папка, в которой хранится кеш, относительно /bitrix/cache/
     * @param string $baseDir Базовая директория кеша. По умолчанию равен cache
     */
    public function __construct($ttl, $uniqueString, $initDir = false, $baseDir = "cache") {
        $this->ttl = $ttl;
        $this->uniqueString = $uniqueString;
        $this->initDir = $initDir;
        $this->baseDir = $baseDir;
    }

    /**
     * @param array $query Массив запроса (select, filter, group, order, limit, offset, runtime)
     * @return array
     */
    public function getList($query) {
        $cache = Cache::createInstance();
        if ($cache->initCache($this->ttl, $this->uniqueString, $this->initDir, $this->baseDir)) {
            $arResult = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $arResult = [];
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