<?
namespace Enimahs;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Application;

class CNewIBlockElements
{
    private $_arFilter = [];
    private $_arSelect = [];
    private $_arOrder = [];
    private $_ttl = 3600000;

    /*public function __construct()
    {
    }

    public function __destruct()
    {
		unset($this->_ttl);
		unset($this->_arOrder);
		unset($this->_arSelect);
		unset($this->_arFilter);
		unset($this);
    }

    /* setters */

    public function setFilter($arFilter)
    {
        $this->_arFilter = $arFilter;
    }

    public function setSelect($arSelect)
    {
        $this->_arSelect = $arSelect;
    }

    public function setOrder($arOrder)
    {
        $this->_arOrder = $arOrder;
    }

    public function setTtl($ttl)
    {
        $this->_ttl = $ttl;
    }

    /* getters */

    public function getFilter()
    {
        return $this->_arFilter;
    }

    public function getSelect()
    {
        return $this->_arSelect;
    }

    public function getOrder()
    {
        return $this->_arOrder;
    }

    private function _getTtl()
    {
        return $this->_ttl;
    }

    /* getList для D7 */
    private function _getListD7()
    {
    	$arResult = [];
		$arParams = [
			'filter' => $this->getFilter(),
			'select' => $this->getSelect(),
			'order' => $this->getOrder(),
			//'cache' => $this->_getTtl(), если необходимо закешировать выборку из БД
		];

        $resResult = \ElementTable::getList($arParams);
        return $resResult;
    }

    /* getList для старого ядра */
    private function _getList()
    {
        $arResult = [];
        $arOrder = $this->getOrder();
        $arFilter = $this->getFilter();
        $arSelect = $this->getSelect();

        $resResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        return $resResult;
    }

    public function getList($arOrder = [], $arFilter = [], $arSelect = [])
    {
		if (!empty($arOrder)) {
			$this->setOrder($arOrder);
		}

		if (!empty($arFilter)) {
			$this->setFilter($arFilter);
		}

		if (!empty($arSelect)) {
			$this->setSelect($arSelect);
		}

		$arOrder = serialize($this->getOrder());
        $arFilter = serialize($this->getFilter());
        $arSelect = serialize($this->getSelect());

        $obCache = Application::getInstance()->getManagedCache();

        $cacheKey = md5($arOrder . $arFilter . $arSelect);
        if ($obCache->read($this->_ttl, $cacheKey)) {
            return $obCache->get($cacheKey);
        } else {
            $resElements = $this->_getList(); // или _getListD7 для ядра D7
            $obCache->set($cacheKey, $resElements);
            return $resElements;
        }
    }
}