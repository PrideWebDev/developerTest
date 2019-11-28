<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Тестовое задание");
?>

<?
/**
 * 
 * 1. Отобразить как обычный компонент в публичной части
 *
 */
$APPLICATION->IncludeComponent(
  'apl:main.element.list',
  'default',
  array(
      'IBLOCK_TYPE' => 'content',
      'IBLOCK_ID' => 11,
      'PAGE_SIZE' => 10,
      'FIELD_CODE' => array(
          'ID',
          'NAME',
          'IBLOCK_ID',
          'PREVIEW_TEXT',
          'PREVIEW_PICTURE',
      ),
      'PROPERTY_CODE' => array(
          'POST',
          'PHONE',
          'EMAIL',
      ),
      'SORT' => array(
          'sort' => 'desc',
          'name' => 'asc'
      ),
      'FILTER' => array(
          'PROPERTY_POST' => 'Тех%'
      )
  )
);
?>

<?
/**
 * 
 * 2. Получить список элементов в виде массива - вариант 1
 *
 */
CBitrixComponent::includeComponentClass('apl:main.element.list');
$component = new MainElementListComponent();

$component->setParams([
    'IBLOCK_ID' => 11,
    'PAGE_SIZE' => 4,
    'FIELD_CODE' => [
        'ID',
        'NAME',
        'IBLOCK_ID',
        'PREVIEW_TEXT',
        'PREVIEW_PICTURE',
    ],
    'PROPERTY_CODE' => [
        'POST',
        'PHONE',
        'EMAIL',
    ],
]);

if ($component->validate()) {
    echo '<pre>';
    var_dump($component->getList()); 
    echo '</pre>';
} else {
    echo implode('<br>', $component->getErrors());
}
?>

<?
/**
 * 
 * 3. Получить список элементов в виде массива - вариант 2
 *
 */
    $list = MainElementListComponent::find()
      ->setParams(['IBLOCK_ID' => 11])
      ->setSelect(['ID', 'NAME', 'PROPERTY_POST'])
      ->setFilter(['PROPERTY_POST' => 'Тех%'])
      ->getList();
    
    echo '<pre>';
    var_dump($list);
    echo '</pre>';
?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
