<?php
    /**
     *  Test script by Pride Web Dev
     *  Get last 5 RSS feed news console application
     *  Date: 14.11.2019
     *  Author: mran0n
     */
    
    class getLast5News {
        
        const FEED_URL = 'https://lenta.ru/rss';
        
        private $newsCount = 5;
        
        private $arResult = [];
        
        function getFeedArray () {
            $this->arResult = simplexml_load_file(self::FEED_URL, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        
        function printResult () {
            if (!empty($this->arResult->channel->item)) {
                $i = 1;
                foreach ($this->arResult->channel->item as $item) {
                    if ($i > $this->newsCount) break;
                    echo "{$item->title}\n\t{$item->link}\n\t{$item->description}\r\n";
                    $i++;
                }
                
            }
        }
        
    }
    
    $CNews = new getLast5News();
    $CNews->getFeedArray();
    $CNews->printResult();
    
?>