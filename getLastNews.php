<?

 $url = "https://lenta.ru/rss";
 $content = file_get_contents($url);

 $items = new SimpleXmlElement($content);

 $count = 0;
 $news = [];

 foreach ($items->channel->item as $key => $item) {
   if($count < 5){
     $title = $item->title;
     $link = $item->link;
     $descr = $item->description;
     $news[] = implode("<br>",[$title,$link,$descr]);

     $count++;
   }else{
     break;
   }
 }

echo "<ul>";
 foreach ($news as $key => $value) {
   echo "<li>$value</li>";
 }
echo "</ul>";
