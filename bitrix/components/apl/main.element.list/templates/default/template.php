<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<? if($arResult['ITEMS']) : ?>
<table width="100%">
  <thead>
    <tr>
      <td>ФИО</td>
      <td>Должность</td>
      <td>Телефон</td>
      <td>E-mail</td>
    </tr>   
  </thead>
  <tbody>
    <? foreach ($arResult['ITEMS'] as $item) : ?>
    <tr>
      <td>
        <span><?= $item['NAME'] ?></span>
        <br>
        <span><?= $item['PREVIEW_TEXT'] ?></span>
      </td>
      <td><?= $item['PROPERTY_POST_VALUE'] ?></td>
      <td><?= $item['PROPERTY_PHONE_VALUE'] ?></td>
      <td><?= $item['PROPERTY_EMAIL_VALUE'] ?></td>
    </tr>  
    <? endforeach ?>
  </tbody>
</table>
<? endif ?>