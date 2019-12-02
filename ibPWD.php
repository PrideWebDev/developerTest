<?php
/**
 * File: ibPWD.php
 * Date: 02/12/2019 / 16:39
 */

/*
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>
<?php
include "ibPWD.php";

$ib = new ibPWD(17);

print_r($ib->getList(
    [
        'order' => [
            'DATE' => 'ASC'
        ],
        'filter' => [
            'ACTIVE_DATE' => 'Y'
        ],
        'select' => ['*'],
        'wo_props' => 1
    ]
));
?>
 */

use \Bitrix\Main\Loader;
use \Bitrix\Main\Data\Cache;

class ibPWD
{
    protected static $module = 'iblock';
    private $iblockID;
    private $lifeTime;
    private $initDir;
    private $baseDir;

    public function __construct($iblockID, $lifeTime = 3600, $initDir = false, $baseDir = 'cache')
    {
        $this->includeModule();

        $this->iblockID = (int) $iblockID;
        $this->lifeTime = $lifeTime;
        $this->initDir = $initDir;
        $this->baseDir = $baseDir;

    }

    public function getIblockID() : int
    {
        return $this->iblockID;
    }

    protected function prepareParams(array $params = null)
    {
        $iblockID = $this->getIblockID();
        $order = is_array($params['order'])
            ?
            $params['order']
            :
            [
                'SORT' => 'ASC',
                'ID' => 'ASC'
            ];

        $filter = is_array($params['filter'])
            ?
            $params['filter']
            :
            [
                'IBLOCK_ID' => $iblockID,
                'ACTIVE' => 'Y',
                'GLOBAL_ACTIVE' => 'Y',
                'ACTIVE_DATE' => 'Y'
            ];

        $filter['IBLOCK_ID'] = $iblockID;

        $groupBy = is_array($params['group']) ? $params['group'] : false;

        $navParams = is_array($params['navParams']) ? $params['navParams'] : false;

        $select = is_array($params['select'])
            ?
            array_merge($params['select'], ['IBLOCK_ID', 'ID'])
            :
            [
                'IBLOCK_ID',
                'ID',
                'NAME',
                'CODE',
                'DETAIL_PAGE_URL',
                'PREVIEW_PICTURE'
            ];

        return compact('order', 'filter', 'groupBy', 'navParams', 'select');
    }

    public function includeModule()
    {
        Loader::includeModule(static::$module);
    }

    public function getList(array $params = null)
    {

        $cache = Cache::createInstance();
        if ($cache->initCache($this->lifeTime, $this->iblockID, $this->initDir, $this->baseDir)) {
            $items = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $items = [];
            $resultParams = $this->prepareParams($params);
            if ($dbItems = \CIBlockElement::GetList(
                $resultParams['order'],
                $resultParams['filter'],
                $resultParams['groupBy'],
                $resultParams['navParams'],
                $resultParams['select']
            )) {

                $i = -1;
                while ($rs = $dbItems->GetNextElement()) {
                    $f = $rs->GetFields();
                    $p = [];
                    if (isset($params['wo_props']) && !$params['wo_props']) {
                        $p = $rs->GetProperties();
                    }

                    if ($params['unique'] && $f[$params['unique']]) {
                        $key = $f[$params['unique']];
                    } else {
                        $key = ++$i;
                    }
                    $items[$key] = array_merge($f, ['PROPERTIES' => $p]);
                }

                return $items;


            } else {
                $cache->abortDataCache();
            }
            $cache->endDataCache($items);
        }
        return $items;


    }
}
