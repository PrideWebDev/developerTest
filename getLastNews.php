<?
$rss = "https://lenta.ru/rss";

$xmlstr = @file_get_contents($rss);
if($xmlstr===false)die('Error connect to RSS: '.$rss);
$xml = new SimpleXMLElement($xmlstr);
if($xml===false)die('Error parse RSS: '.$rss);

$xml = @simplexml_load_file( $rss);
if($xml===false)die('Error parse RSS: '.$rss);

foreach($xml->xpath('//item') as $item){
	$i++;
	if ($i>5) break;
	echo "
	<h3>{$item->title}</h3>
	<a href='{$item->link}' target='_blank'>{$item->link}</a><br />
	{$item->description}<hr />
	";
}

/*
  <guid>https://lenta.ru/news/2019/10/29/treasury/</guid>
  <title>Найдены сокровища «Исламского государства»</title>
  <link>https://lenta.ru/news/2019/10/29/treasury/</link>
  <description>
    <![CDATA[Иракские пастухи обнаружили в пустыне тайник, в котором боевики «Исламского государства» спрятали деньги и ценности, сообщил Мохаммед Али Саджет, который называется одним из ближайших соратников убитого главаря ИГ. По его словам, там хранились наличные, золото и серебро общей стоимостью почти 25,7 миллиона долларов.]]>
  </description>
  <pubDate>Tue, 29 Oct 2019 20:05:03 +0300</pubDate>
  <enclosure url="https://icdn.lenta.ru/images/2019/10/29/20/20191029200641330/pic_80985f532c89aa26b874135e890f295a.jpg" type="image/jpeg" length="137480"/>
  <category>Мир</category>
  */