
<?php

class Runner_Crawler_Adapter_Fgc implements Runner_Crawler_Adapter_Interface {

	public function getHtmlData($url) {
		return file_get_contents($url);
	}

}