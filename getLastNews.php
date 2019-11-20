<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/classes/general/xml.php");
$APPLICATION->SetTitle("Тест RSS");
?>
<?php
$news = [];
if ($rss = file_get_contents("https://lenta.ru/rss")) {
    $xml = new CDataXML();
    $xml->LoadString($rss);
    $arData = $xml->GetArray();
    foreach ($arData['rss']['#']['channel'][0]['#']['item'] as $item) {
        $news[] = [
            'tilte' => $item['#']['title'][0]['#'],
            'link' => $item['#']['link'][0]['#'],
            'description' => $item['#']['description'][0]['#'],
        ];
    }
}
?>

<?php foreach ($news as $item): ?>
    <h3><?= $item['tilte'] ?></h3>
    <div><?= $item['description'] ?></div>
    <div><a href="<?= $item['link'] ?>" target="_blank"><?= $item['link'] ?></a></div>
    <hr/>
<?php endforeach; ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>