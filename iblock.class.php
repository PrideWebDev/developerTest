<?php
class CiBlock 
{
    public static function getCachedList($filter, $select = false, $sort = ["NAME"=>"ASC"], $pageParams = false)
    {
        if(!CModule::IncludeModule("iblock")) return false;
        if(empty($filter)) return false;
    
        $result = false;
        $cache = new CPHPCache;
        $cache_params = [
            'func' => 'CIBlockElement::GetList',
            'arSelect' => $select,
            'sort' => $sort,
            'pageParams' => $pageParams,
        ];
        
        $cache_params = array_merge($filter, $cache_params);
        
        $cache_id = md5(serialize($cache_params));
        if($cache->InitCache(3600, $cache_id, "/")) {
            $result = $cache->GetVars();
        }
        else {
            $list = CIBlockElement::GetList($sort, $filter, false, $pageParams, $select);
            while($element = $list->GetNext())
            {
                $result[] = $element;
            }
        }

        if($cache->StartDataCache())
            $cache->EndDataCache($result);

        return $result;
    }
}
