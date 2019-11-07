<?
$ch = curl_init();
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
$headers = array( 'Expect:','Connection: Keep-Alive','Accept-Charset: utf-8,windows-1251;q=0.7,*;q=0.7' );
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL, 'https://lenta.ru/rss');
$rss_str = curl_exec($ch);
if(!$rss_str) {
    print "Oooops. Can't get rss stream.\n";
    exit;
}
$rss = simplexml_load_string($rss_str, 'SimpleXMLElement', LIBXML_NOCDATA);
foreach ($rss->channel->item as $item) {
    $mas[] = "\nНазвание: \n".$item->title."\nСсылка:\n".$item->link."\nАнонс:\n".$item->description."\n \n";
}
$new_mas = array_slice($mas, -5);
foreach ($new_mas as $news){
    print $news;
}
?>
