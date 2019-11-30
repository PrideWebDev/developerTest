<?php
/**
 * Зачем эти пляски с бубном вокруг кеша, 
 * если он уже реализован в родителе класса \Bitrix\Iblock\ElementTable
 *
 * Класс:	Bitrix\Main\ORM\Data\DataManager
 * Файл:	bitrix/modules/main/lib/orm/data/datamanager.php
 * Метод:	getList
 * Строка:	488
 * 
 * возможность задать сортировку, фильтр, список выбираемых полей при вызове метода уж куда гибче то стандартного
 
 
 array(
	'order' => array('SORT' => 'ASC'),
	'select' => array('ID', 'NAME', 'IBLOCK_ID', 'SORT', 'TAGS'),
	'filter' => array('IBLOCK_ID' => 4),
	'group' => array('TAGS'),
	'limit' => 1000,
	'offset' => 0,
	'runtime' => array(),
	'data_doubling' => false,
	'cache' => array(
		'ttl' => 3600,
		'cache_joins' => true
	),
 )
 
 */
namespace TestPackage;

use \Bitrix\Iblock\ElementTable;

/**
 * Class ClassIBlock
 * @package TestPackage
 */
 class ClassIBlock {
	
	/**
	 * @param array $query Массив запроса (select, filter, group, order, limit, offset, runtime, cache)
	 * @return array
	*/
	public static function getList($query)
	{
		return ElementTable::getList($query)->fetchAll();
	}
 }
