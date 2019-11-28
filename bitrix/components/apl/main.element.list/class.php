<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc as Loc,
    Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

/**
 *  Класс по работе со списками элементов инфоблоков
 */
class MainElementListComponent extends \CBitrixComponent {

    /** Страница отображения ошибок */
    const PAGE_ERRORS = 'errors';

    /** Количество элементов на странице по умолчанию */
    const DEFAULT_PAGE_SIZE = 10;
    /** Количество элементов на странице по умолчанию */
    const DEFAULT_CACHE_TIME = 3600;
    /** Папка хранения кеш файлов по умолчанию */
    const DEFAULT_CACHE_DIR = '/main_element_list/';

    /** @var string Файл шаблона */
    protected $page = 'template';   
    
    /** @var array Признак необходимости валидации */
    protected $needValidate = false;

    /** @var array Правила параметров компонента */
    protected $rulesParams = [
        [['IBLOCK_ID'], 'required'],
        [['FIELD_CODE', 'PROPERTY_CODE', 'SORT', 'FILTER'], 'array'],
    ];
    
    /** @var array Ошибки */
    private $_errors = [];

    /** @var array Сообщения */
    private $_summary = [];

    /**
     * Идентификатор шаблона компонента
     * @return string
     */
    public function getUnique() {
        return md5($this->GetName() . '.' . $this->GetTemplateName());
    }
    
    /**
     * @inheritdoc
     */
    public function executeComponent() {
        $this->setSefDefaultParams();

        if (!$this->validate()) {
            $this->page = self::PAGE_ERRORS;
        }

        $this->setResult();
        $this->includeComponentTemplate($this->page);

        return $this;
    }    
        
    /**
     * @return this
     */
    public static function find() {
        $className = get_called_class();
        
        $class = new $className();
        $class->needValidate = true;
        
        return $class;
    }

    /**
     * Установить параметры компонента
     */
    public function setParams(array $params) {        
        $this->arParams = $params;
        $this->setSefDefaultParams();
        $this->needValidate = true;
        
        return $this;
    }
    
    /**
     * Установить параметры выборки
     */
    public function setSelect(array $select) {
        $this->arParams['FIELD_CODE'] = $select;       
        
        return $this;
    }
    
    /**
     * Установить параметры фильтра
     */
    public function setFilter(array $filter) {
        $this->arParams['FILTER'] = $filter;       
        
        return $this;
    }

    /**
     * Валидация
     *
     * @return boolean
     */
    public function validate() {
        if ($this->validateParams() && $this->validatePermission()) {            
            $this->needValidate = false;
            
            return true;
        }

        return false;
    }   

    /**
     * Получить список элементов
     *
     * @return []
     */
    public function getList($select = [], $filter = [], $sort = []) {
        if($this->needValidate && !$this->validate()) {
            return false;
        }
        
        $cache = \Bitrix\Main\Data\Cache::createInstance();
        
        if ($cache->initCache($this->arParams['CACHE_TIME'], md5($this->getCacheId()), self::DEFAULT_CACHE_DIR)) {
          
            return $cache->getVars();
        }
          
        $result = [];

        $rsElements = CIBlockElement::GetList(
            $this->getSortByList($sort),
            $this->getFilterByList($filter),
            false,
            ['nPageSize' => $this->arParams['PAGE_SIZE']],
            $this->getSelectByList($select)
        );

        while ($item = $rsElements->Fetch()) {
            $result[] = $item;
        }

        if ($cache->startDataCache()) {
            $cache->endDataCache($result); 
        }

        return $result;
    }

    /**
     * Проверить наличие ошибок
     *
     * @return boolean
     */
    public function hasErrors() {
        if ($this->_errors) {
            return true;
        }

        return false;
    }

    /**
     * Получить сообщение или массив об ошибках
     *
     * @return array|string
     */
    public function getErrors() {
        return $this->_errors;
    }

    /**
     * Получить сообщение или массив об успешных действиях
     *
     * @return array|string
     */
    public function getSummary() {
        return $this->_summary;
    }

    /**
     * Получить обязательные параметры компонента
     *
     * @return []
     */
    protected function getRulesParams() {
        return $this->rulesParams;
    }

    /**
     * Валидация параметров
     * 
     * @return boolean
     */
    protected function validateParams() {
        foreach ($this->getRulesParams() as list($params, $rule)) {
            if (is_array($params)) {
                foreach ($params as $param) {
                    $this->checkRuleParam($param, $rule);
                }
            } else {
                $this->checkRuleParam($params, $rule);
            }
        }

        if ($this->_errors) {
            return false;
        }

        return true;
    }

    /**
     * Валидация прав
     * 
     * @return boolean
     */
    protected function validatePermission() {
        if (CIBlock::GetPermission($this->arParams['IBLOCK_ID']) < 'D') {            
            $this->_errors[] = Loc::getMessage('MEL__ERROR__ACCESS_RIGHTS_R');

            $this->page = self::PAGE_ERRORS;

            return false;
        }

        return true;
    }

    /**
     * Валидация переменных
     *
     * @return boolean
     */
    protected function validateVariables($role = '') {
        return true;
    }

    /**
     * Проверка параметра компонента на правила
     *
     * @return boolean
     */
    protected function checkRuleParam($param, $rule) {
        if ($rule == 'required' && !$this->arParams[$param]) {
            $this->_errors[] = Loc::getMessage('MEL__ERROR__INCORRECTLY_PARAM', [
                '#PARAM_CODE#' => $param,
            ]);

            return false;
        }

        if ($rule == 'array' && $this->arParams[$param] && !is_array($this->arParams[$param])) {
            $this->_errors[] = Loc::getMessage('MEL__ERROR__INCORRECTLY_PARAM', [
                '#PARAM_CODE#' => $param,
            ]);

            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function setSefDefaultParams() {
        if (!$this->arParams['FILTER']) {
            $this->arParams['FILTER'] = [];
        }
        
        if (!$this->arParams['SORT']) {
            $this->arParams['SORT'] = [
                'sort' => 'asc',
                'name' => 'asc'
            ];
        }
        
        if (!$this->arParams['FIELD_CODE']) {
            $this->arParams['FIELD_CODE'] = [
                'ID',
                'NAME',
                'IBLOCK_ID',
            ];
        }
        
        if (!$this->arParams['PROPERTY_CODE']) {
            $this->arParams['PROPERTY_CODE'] = [];
        }

        if (!$this->arParams['PAGE_SIZE']) {
            $this->arParams['PAGE_SIZE'] = self::DEFAULT_PAGE_SIZE;
        }
        
        if(!$this->arParams['CACHE_TIME']){
           $this->arParams['CACHE_TIME'] = 360000;
        }
    }

    /**
     * @inheritdoc
     */
    protected function setResult() {
        if ($this->hasErrors()) {
            $this->arResult = [
                'ERROR_TEXT' => implode('<br/>', $this->_errors)
            ];

            return;
        }

        $this->arResult = [
            'ITEMS' => $this->getList(),
            'UNIQUE' => $this->getUnique()
        ];
    }
    
    /**
     * Получить св-ва выборки
     *
     * @return []
     */
    protected function getPropertiesByList($properties = []) {
        $result = array_merge($this->arParams['PROPERTY_CODE'], $properties);
        
        return array_map(function($code){
            return 'PROPERTY_' . $code;
        }, $result);
    }

    /**
     * Получить поля выборки
     *
     * @return []
     */
    protected function getSelectByList($select = []) {
        return array_merge($this->arParams['FIELD_CODE'], $select, $this->getPropertiesByList());
    }

    /**
     * Получить параметры выборки
     *
     * @return []
     */
    protected function getFilterByList($filter = []) {
        return array_merge(
          array_filter([ 
            'IBLOCK_TYPE' => $this->arParams['IBLOCK_TYPE'],
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID']
          ]), 
          $this->arParams['FILTER'], 
          $filter
        );
    }
    
    /**
     * Получить параметры сортировки выборки
     *
     * @return []
     */
    protected function getSortByList($sort = []) {
        return $sort ?: $this->arParams['SORT'];
    }
}

?>
