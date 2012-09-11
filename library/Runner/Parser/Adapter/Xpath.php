<?php

class Runner_Parser_Adapter_Xpath implements Runner_Parser_Adapter_Interface {

    public function getUrls($data) {
        $dom = new DOMDocument();
        @$dom->loadHTML($data);
        $xpath = new DOMXPath($dom);
        $elements = $xpath->evaluate("//a/@href");
        $urls = array();
        foreach ($elements as $e) {

            $url  =  $e->nodeValue;
            $urls[]  = $url;
        }
		
        return array_unique($urls);
    }

}