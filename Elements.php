<?php


namespace project\bxHelper\iblock;

use Bitrix\Iblock\ElementTable;

/**
 * Class Elements
 * @package project\bxHelper\iblock
 * Чисто для развлечения,
 * кеширование выборки решаеться добавление одного параметра.
 * В пулреквестах у вас есть подобные решения.
 *
 * $elements = new Elements();
 * $rows = $elements->inIBlock(777)->active()->getIds();
 * $rows = $elements->flush()->inIBlock(777)->like('NAME', 'ля')->select(['ID', 'NAME', 'CODE'])->order(['NAME' => 'asc'])->getAll();
 * $rows = $elements->flush()->inIBlock(777)->notLike('NAME', 'те')->select(['ID', 'NAME', 'CODE'])->order(['NAME' => 'asc'])->getAll();
 * $rows = $elements->flush()->inIBlock(777)->isEmpty('PREVIEW_PICTURE')->getAll();
 * $rows = $elements->flush()->inIBlock(777)->startWith('NAME', 'Дл')->getAll();
 * $rows = $elements->flush()->inIBlock(777)->endWith('NAME', 'ег')->getAll();
 * $rows = $elements->flush()->inIBlock(777)->active()->count();
 *
 */
class Elements
{

    const DEFAULT_CACHE_TIME = 3600;

    private $cacheTime = self::DEFAULT_CACHE_TIME;
    private $filter = [];
    private $select;
    private $order = [];
    private $limit;
    private $offset = 0;
    /**
     * @var \Bitrix\Main\ORM\Query\Query
     */
    private $query;


    /**
     * Elements constructor.
     * Можно при создании сразу же задать параметры
     * (new Elements(['filter'=>['IBLOCK_ID'=>$id]]))->get(5);
     * @param array $params - Стандартные параметры выборки для orm
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct($params = null)
    {
        \Bitrix\Main\Loader::includeModule("iblock");

        $this->withFilter($params['filter'])
            ->select($params['select'])
            ->order($params['order']);
    }

    /**
     * Для установки стандартного параметра select
     * @param $fields
     * @return $this
     */
    public function select($fields)
    {
        if (isset($fields) or is_array($fields))
            $this->select = $fields;
        return $this;
    }

    /**
     * Для установки стандартного параметра filter
     * @param array $filter
     * @return $this
     */
    public function withFilter($filter)
    {
        if (isset($filter) and is_array($filter))
            $this->filter = $filter;
        return $this;
    }

    /**
     * Для добавления условния в фильтр
     * @param $param - условие по полю
     * @param array|int|string|null $value - значение для условия
     * @return $this
     */
    public function withCondition($param, $value)
    {
        $this->filter[$param] = $value;
        return $this;
    }

    /**
     * установка стандартного параметра order
     * @param $orders
     * @return $this
     */
    public function order($orders)
    {
        $this->order = $orders;
        return $this;
    }

    /**
     * Указываем лимит выборки
     * @param $limit
     */
    public function limit($limit)
    {
        $this->limit = ((int)$limit > 0) ? (int)$limit : null;
    }

    /**
     * Впринципе, можно сделать универсальный класс и использовать любую сущность от Main\Entity\DataManager
     * @return \Bitrix\Main\ORM\Query\Result
     */
    private function execQuery()
    {
        $this->query = new \Bitrix\Main\ORM\Query\Query(ElementTable::getEntity());

        $this->query->setSelect(['*']);
        if (isset($this->select)) $this->query->setSelect($this->select);

        if ((int)$this->limit > 0) $this->query->setLimit($this->limit);

        $this->query->setFilter($this->filter);
        $this->query->setOffset($this->offset);
        $this->query->setCacheTtl($this->cacheTime);
//        $this->query->cacheJoins(true); // ну такое...
        return $this->query->exec();
    }


    /**
     * Все что дальше будет это просто профанация.
     * Все можно сделать через \Bitrix\Main\ORM\Query\Query
     */

    /**
     * Условие соответсвия
     * $elements = (new Elements())->equal('NAME',$someName)->getAll;
     * @param $field
     * @param $value
     * @return Elements
     */
    public function equal($field, $value)
    {
        $prefix = is_array($value) ? '@' : '=';
        return $this->withCondition($prefix . $field, $value);
    }

    /**
     * Условие не соответсвия
     * $elements = (new Elements())->notEqual('NAME',$someName)->getAll;
     * @param $field
     * @param $value
     * @return Elements
     */
    public function notEqual($field, $value)
    {
        $prefix = is_array($value) ? '!@' : '!=';
        return $this->withCondition($prefix . $field, $value);
    }

    /**
     * Условие по вхождению подстроки
     * $elements = (new Elements())->like('NAME',$subStr)->getAll;
     * @param $field
     * @param $value
     * @return Elements
     */
    public function like($field, $value)
    {
        $prefix = '%';
        return $this->withCondition($prefix . $field, $value);
    }

    /**
     * Условие исключения элементов по вхождению подстроки
     * $elements = (new Elements())->notLike('NAME',$subStr)->getAll;
     * @param $field
     * @param $value
     * @return Elements
     */
    public function notLike($field, $value)
    {
        $prefix = '!%';
        return $this->withCondition($prefix . $field, $value);
    }

    /**
     * НАходим елементы с пустыми полями
     * $elements = (new Elements())->isEmpty('CODE')->getAll;
     * @param $field
     * @return Elements
     */
    public function isEmpty($field)
    {
        return $this->equal($field, null);
    }

    /**
     * Условие поиска элементов, значения полей которых начинаются с подстроки
     * $elements = (new Elements())->startWith('NAME',$subStr)->getAll;
     * @param $field
     * @param $value
     * @return Elements
     */
    public function startWith($field, $value)
    {
        return $this->withCondition($field, $value . '%');
    }

    /**
     * Условие поиска элементов, значения полей которых оканчиваются на подстроку
     * $elements = (new Elements())->startWith('NAME',$subStr)->getAll;
     * @param $field
     * @param $value
     * @return Elements
     */
    public function endWith($field, $value)
    {
        return $this->withCondition($field, '%' . $value);
    }


    /**
     * Условие активности
     * $elements = (new Elements())->inIBlock(777)->active()->getIds();
     * @return Elements
     */
    public function active()
    {
        return $this->equal('ACTIVE', 'Y');
    }

    /**
     * условие активности
     * @return Elements
     */
    public function notActive()
    {
        return $this->equal('ACTIVE', 'N');
    }

    /*
     * Также будут полезны методы activeNow, notActiveNow добавляющие условия выборки элементов действитель активных/неактивных сейчас
     */

    /**
     * просто условие id инфоблока
     * @param $iBlockIds
     * @return $this
     */
    public function inIBlock($iBlockIds)
    {
        $this->filter['IBLOCK_ID'] = $iBlockIds;
        return $this;
    }


    /**
     * так на всякии, а то вдруг
     * @return \Bitrix\Main\ORM\Query\Result
     */
    public function getIterator()
    {
        return $this->execQuery();
    }

    /**
     * отдать все
     * @return array
     */
    public function getAll()
    {
        return $this->execQuery()->fetchAll();
    }

    /**
     * отдать, с лимитом
     * @param int $limit
     * @return array
     */
    public function get($limit = -1)
    {
        $this->limit($limit);
        return $this->execQuery()->fetchAll();
    }

    /**
     * вернуть количество записей
     * @return int
     */
    public function count()
    {
        return $this->execQuery()->getSelectedRowsCount();
    }

    /**
     * вернуть только id элементов
     * @return array
     */
    public function getIds()
    {
        $this->select(['ID']);
        $result = [];
        foreach ($this->getAll() as $row) {
            $result[] = $row['ID'];
        }
        return $result;
    }

    /**
     * обнуляем параметры
     * @return mixed
     */
    public function flush()
    {
        return $this->withFilter([])->select(null)->order([]);
    }

    /*
     * Тут можно до бесконечности сахарить
     */
}