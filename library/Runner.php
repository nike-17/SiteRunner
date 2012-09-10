<?php

// Register autoloader
require_once dirname(__FILE__) . '/Runner/Autoloader.php';
Runner_Autoloader::register();

class Runner extends Runner_Options {

	protected $_siteHost;
	protected $_options = array(
		'siteUrl' => ''
	);

	public function __construct(array $options = array()) {
		parent::__construct($options);
	}

	public function getSiteHostName() {
		if (!$this->_siteHost) {
			$this->_siteHost = $this->getHostName($this->getOption('siteUrl'));
		}
		return $this->_siteHost;
	}

	public function getHostName($url) {
		$parsed_url = parse_url($url);
		if (!isset($parsed_url['host'])) {
			throw new Runner_Exception_Runner("Invalid site url {$url}");
		}
		return $url;
	}

}