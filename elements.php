<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27.10.2019
 * Time: 16:46
 */


//namespace Module\Lib\; ToDo set namespace

/**
 * usage
 *
 * Elements::getInstance()->ormMode(false);
 * Elements::getInstance()->setSelect(array('NAME'));
 * Elements::getInstance()->setOrder(array('NAME' => 'DESC'));
 * Elements::getInstance()->setLimit(40);
 * Elements::getInstance()->setPage(2);
 * Elements::getInstance()->setFilter(array('ID' => 100));
 * $result = Elements::getInstance()->getElementsArray();
 */
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Application;

class Elements{
    static $instance = null;
    const IBLOCK_ID = 9;
    protected $filter = array();
    protected $select = array();
    protected $order = array();
    protected $offset = 0;
    protected $limit = 0;
    protected $orm = true;
    const CACHE_KEY = 'class_for_test';
    public static function getInstance(){
        if (!self::$instance instanceof self) self::$instance = new self();
        return self::$instance;
    }
    public function setFilter(array $filter){
        $this->filter = $filter;
    }
    public function setSelect(array $select){
        $this->select = $select;
    }
    public function setOrder(array $order){
        $this->order = $order;
    }
    public function setPage(int $offset){
        $this->offset = $offset;
    }
    public function setLimit(int $limit){
        $this->limit = $limit;
    }
    public function ormMode(bool $orm){
        $this->orm = $orm;
    }
    public function getElementsArray(){
        if ($this->orm){
            return $this->getListOrm();
        }else{
            return $this->getList();//if you need compatibility
        }
    }
    private function getFilter(){
        return array_merge(array('IBLOCK_ID' => self::IBLOCK_ID), $this->filter);
    }
    private function getSelect(){
        return array_merge(array('ID', 'IBLOCK_ID'), $this->select);
    }
    private function getOrder(){
        return array_merge($this->order, array('ID' => 'ASC'));
    }
    /** @return array Element list iblock_id = 9*/
    private function getListOrm(){
        $param = array();
        $result = array();
        $param['filter'] = $this->getFilter();
        $param['select'] = $this->getSelect();
        $param['order'] = $this->getOrder();
        $param['limit'] = $this->limit;
        $param['offset'] = $this->offset;
        $param['cache'] = array('ttl' => 3600);
        $db = ElementTable::getList($param);
        while ($res = $db->fetch()){
            $result[$res['ID']] = $res;
        }
        return $result;
    }
    private function getElementsEx(){
        $result = array();
        $filter = $this->getFilter();
        $select = $this->getSelect();
        $order = $this->getOrder();
        $nav = array(
            'nPageSize' => $this->limit,
            'iNumPage' => $this->offset
        );
        $el = new \CIBlockElement();
        $db = $el->GetList($order, $filter, false, $nav, $select);
        while ($res = $db->Fetch()){
            $result[$res['ID']] = $res;
        }
        return $result;
    }
    private function getList(){
        $cache = Application::getInstance()->getManagedCache();
        $clearCache = (Application::getInstance()->getContext()->getRequest()->get('clear_cache') === 'Y');
        $cacheKey = self::CACHE_KEY.'_'.$this->limit.'_'.$this->offset;
        if ($clearCache) $cache->clean($cacheKey);
        if ($cache->read('3600', $cacheKey)){
            return $cache->get($cacheKey);
        }else{
            $tmp = $this->getElementsEx();
            $cache->set($cacheKey, $tmp);
            return $tmp;
        }
    }
}