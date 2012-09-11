<?php

class Runner_Processor_Xpath implements Runner_Processor_Interface {

	public function find($data, $find) {
		$dom = new DOMDocument();
		@$dom->loadHTML($data);

		$xpath = new DOMXPath($dom);
		$elements = $xpath->evaluate($find);
		var_Dump($elements);
		die();
		return $elements;
		$urls = array();
		foreach ($elements as $e) {

			$url = $e->nodeValue;
			$urls[] = $url;
		}

		return array_unique($urls);
	}

	protected function _nodeContent($n, $outer = false) {
		if (!$n) {
			return;
		}
		$d = new DOMDocument('1.0');
		$b = $d->importNode($n->cloneNode(true), true);
		$d->appendChild($b);
		$h = $d->saveHTML();
		// remove outter tags 
		if (!$outer)
			$h = substr($h, strpos($h, '>') + 1, -(strlen($n->nodeName) + 4));
		return $h;
	}

	public function getDivContentByClass($html, $class) {
		$query = "//div[@class='$class']";
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		$xpath = new DOMXPath($dom);
		$result = $xpath->query($query);
		$data = $this->_nodeContent($result->item(0));
		return $data;
	}

	public function getSpanContentByClass($html, $class) {
		$query = "//span[@class='$class']";
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		$xpath = new DOMXPath($dom);
		$result = $xpath->query($query);
		$data = $this->_nodeContent($result->item(0));
		return $data;
	}

	public function getSpanContentByProperty($html, $class) {
		$query = "//span[@property='$class']";
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		$xpath = new DOMXPath($dom);
		$result = $xpath->query($query);
		$data = $this->_nodeContent($result->item(0));
		return $data;
	}

}