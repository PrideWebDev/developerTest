<?php
    
    /**
     *  Test script by Pride Web Dev
     *  Get IBlock elements by cached method 
     *  Date: 14.11.2019
     *  Author: mran0n
     */

    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
     
    class CIBlockElementsCachedList {
        
        public $cacheID = "cache_prefix_";
        
        private $cacheLifeTime = 30*60;
        
        public $order = [];
        
        public $filter = [];
        
        public $select = [];
        
        public $pageNum = 0;
        
        public $elementCnt = 0;
        
        public $defautIBlockID = 1;
        
        // Initial
        function __construct ($order = [], $filter = [], $select = [], $pageNum = 0, $elementCnt = 0) {
            
            if(!CModule::IncludeModule("iblock")) {
                return false;
            }
            
            if (!empty($order) && is_array($order)) {
                $this->setOrder($order);
            }
            
            if (!empty($filter) && is_array($filter)) {
                $this->setFilter($filter);
            }
            
            if (!empty($select) && is_array($select)) {
                $this->setSelect($select);
            }
            
            if ($pageNum > 0) {
                $this->setPageNum($pageNum);
            }
            
            if ($elementCnt > 0) {
                $this->setElementCnt($elementCnt);
            }
            
        }
        
        // Set order array
        function setOrder (array $order) {
            $this->order = array_merge($order, ["ID" => "ASC"]);
        }
        
        // Set filter array
        function setFilter (array $filter) {
            $this->filter = array_merge(["IBLOCK_ID" => $this->defautIBlockID], $filter);
        }
        
        // Set select array
        function setSelect (array $select) {
            $this->select = array_merge ($select, ["IBLOCK_ID", "ID", "NAME"]);
        }
        
        // Set page number
        function setPageNum (int $pageNum) {
            $this->pageNum = $pageNum;
        }
        
        // Set element count to 
        function setElementCnt (int $elementCnt) {
            $this->elementCnt = $elementCnt;
        }
        
        // Set cache life time
        function setCacheLifeTime (int $cacheLifeTime) {
            $this->cacheLifeTime = $cacheLifeTime;
        }
        
        // Get cache preffix string
        function getCachePreffixString () {
            return $this->cacheID . "_" . $this->cacheLifeTime . "_" . $this->pageNum;
        }
        
        // Get iblock elements with cache
        function getCachedElementList () {
            
            $CPHPCache = new CPHPCache; 
            if($CPHPCache->InitCache($this->cacheLifeTime, $this->getCachePreffixString(), "/")) {
                $arResult = $CPHPCache->GetVars();
            } else {
                $el = new CIBlockElement();
                $dbRes = $el->GetList(
                    $this->order, 
                    $this->filter, 
                    false, [
                        "nPageSize" => $this->pageNum,
                        "iNumPage" => $this->elementCnt
                    ], 
                    $this->select
                );
                while ($obElement = $dbRes->Fetch()) {
                    $arResult["ITEMS"][] = $obElement;
                }
            }
            
            if($CPHPCache->StartDataCache()) {
                $CPHPCache->EndDataCache(["ITEMS" => $arResult["ITEMS"]]); 
            }
            
            return $arResult["ITEMS"];
            
        }
        
    }
    
?>