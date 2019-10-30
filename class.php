<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?php
//Выборка элементов инфоблока через новый класс
class getElements {
	
	var $arResult;	
	
	function __construct($arParams) {
		$this->arResult = $this->get_elements($arParams);
	}
	
	function get_elements($arParams) { print_r($arParams["IBLOCK_ID"]);
		
		$arOrder = $arParams["ORDER"];
		$arSelect = $arParams["SELECT"];
		$arFilter = $arParams["FILTER"];
		$res = CIBlockElement::GetList($arOrder , $arFilter, false, $arParams["LIMIT"], $arSelect);
		
		while($ob = $res->GetNextElement())
		{
			$arFields[] = $ob->GetFields();			
		}
		
		return $arFields;
	}
}



if(CModule::IncludeModule('iblock')){	
	
	$obCache = new CPHPCache();

	
if($obCache->InitCache(3600, "elements", "/"))// Если кэш валиден
{
   $vars = $obCache->GetVars();// Извлечение переменных из кэша
   $arResult = $vars["RESULT"];
}
elseif($obCache->StartDataCache())// Если кэш невалиден
{	

$ge = new getElements(Array("IBLOCK_ID" => 29, "ORDER" => Array("SORT" => "ASC"),  "SELECT" =>  Array("ID", "NAME"), "LIMIT" => Array("nPageSize"=>50), "FILTER" =>  Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ACTIVE"=>"Y")));
$arResult = $ge->arResult;

$obCache->EndDataCache(array(
        "RESULT"    => $arResult
        )); 

}

print_r($arResult);
}
?>