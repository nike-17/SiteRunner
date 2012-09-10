<?php

class Runner_Parser_Adapter_Xpath implements Runner_Parser_Adapter_Interface {

	public function getLinks($data) {
		$xpath = new DOMXPath($data);
		return $xpath->evaluate("/html/body//a");
	}

}