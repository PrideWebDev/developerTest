<?
use \Bitrix\Main\Loader;
Loader::includeModule("iblock");

class ElementsCollector
{

$sort = ['sort'=>'asc'];
$filter = [];
$fields = [];

  private function Validator($params)
  {
    //Сначала проводим валидацию фильтра, предполагается что он обязательно должен быть заполненым
    if(empty($params['filter'])){
      return ['type'  =>  false, 'msg'  =>  'Empty filter'];
    }else if(!is_array($params['filter'])){
      return ['type'  =>  false, 'msg'  =>  'Invalid filter'];
    }else if(empty($params['filter']['IBLOCK_ID'])){
      return ['type'  =>  false, 'msg'  =>  'Empty IBLOCK_ID'];
    }else{
      $this->filter = $params['filter'];
    }

    //Проверяем сортировку
    //Предполагается что мыиспользуем простую сортировку по возрастанию и убыванию
    if(!empty($params['sort'])){
      if(!is_array($params['sort'])){
        return ['type'  =>  false, 'msg'  =>  'Invalid sort'];
      }else if(count($params['sort']) > 1){
        return ['type'  =>  false, 'msg'  =>  'Invalid sort'];
      }else if(!in_array($params['sort'][array_key_first($params['sort'])],['asc','desc'])){
        return ['type'  =>  false, 'msg'  =>  'Invalid sort'];
      }else{
        $this->sort = $params['sort'];
      }
    }

    //Проверяем поля
    if(!empty($params['fields'])){
      if(!is_array($params['fields'])){
        return ['type'  =>  false, 'msg'  =>  'Invalid fields'];
      }else{
        $this->fields = $params['fields'];
      }
    }
    return ['type'  => true];
  }

  public function Collector($params)
  {
    $validator = $this->Validator($params);
    if($validator['type']){

      //Для удобства 
      $sort = &$this->sort;
      $filter = &$this->filter;
      $fields = &$this->fields;

      $sample = CIBlockElement::GetList($sort,$filter,false,false,$fields);
      return $sample;
    }else{
      return $validator['msg'];
    }
  }


}
