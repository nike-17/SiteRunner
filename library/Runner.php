<?php

// Register autoloader
require_once dirname(__FILE__) . '/Runner/Autoloader.php';
Runner_Autoloader::register();

class Runner {

	protected $_siteHost;

	/**
	 *
	 * @var Runner_Planner
	 */
	protected $_planner;

	/**
	 *
	 * @var Runner_Crawler 
	 */
	protected $_crawler;

	/**
	 *
	 * @var Runner_Parser 
	 */
	protected $_parser;

	/**
	 *
	 * @var Runner_Processor_Interface
	 */
	protected $_processor;

	/**
	 * Array of options
	 * @var array 
	 */
	protected $_options = array(
		'siteUrl' => '',
		'crawlerAdapterName' => '',
		'parserAdapterName' => '',
		'processorName' => ''
	);

	/**
	 * Array of options
	 * @param array $options
	 */
	public function __construct(array $options = array()) {
		$options = array_merge($this->_options, $options);
		$this->setOptions($options);
		$this->_init();
	}

	/**
	 * 
	 * @return Runner_Planner
	 */
	public function getPlanner() {
		return $this->_planner;
	}

	/**
	 *  Set crawlerAdapterName
	 * @param string $crawlerAdapterName
	 * @return Runner
	 * @throws Runner_Exception_Runner
	 */
	public function setCrawlerAdapterName($crawlerAdapterName) {
		$crawlerAdapterName = 'Runner_Crawler_Adapter_' . ucfirst($crawlerAdapterName);
		if (!class_exists($crawlerAdapterName)) {
			throw new Runner_Exception_Runner("Adapter {$crawlerAdapterName} is not exists");
		}
		$this->_options['crawlerAdapterName'] = $crawlerAdapterName;
		return $this;
	}

	/**
	 * Set parserAdapterName
	 * @param string $parserAdapterName
	 * @return \Runner
	 * @throws Runner_Exception_Runner
	 */
	public function setParserAdapterName($parserAdapterName) {
		$parserAdapterName = 'Runner_Parser_Adapter_' . ucfirst($parserAdapterName);
		if (!class_exists($parserAdapterName)) {
			throw new Runner_Exception_Runner("Adapter {$parserAdapterName} is not exists");
		}
		$this->_options['parserAdapterName'] = $parserAdapterName;
		return $this;
	}

	public function setProcessorName($processorName) {
		$processorName = 'Runner_Processor_' . ucfirst($processorName);
		if (!class_exists($processorName)) {
			throw new Runner_Exception_Runner("Processor {$processorName} is not exists");
		}
		$this->_options['processorName'] = $processorName;
		return $this;
	}

	/**
	 * 
	 * @return Runner_Crawler
	 */
	public function getCrawler() {
		return $this->_crawler;
	}

	/**
	 * 
	 * @return Runner_Parser
	 */
	public function getParser() {
		return $this->_parser;
	}

	/**
	 * 
	 * @return Runner_Processor_Interface
	 */
	public function getProcessor() {
		return $this->_processor;
	}

	/**
	 * Get site host name
	 * @return string
	 */
	public function getSiteHostName() {
		if (!$this->_siteHost) {
			$this->_siteHost = $this->getHostName($this->getOption('siteUrl'));
		}
		return $this->_siteHost;
	}

	/**
	 * Get host name by url
	 * @param string $url
	 * @return string
	 * @throws Runner_Exception_Runner
	 */
	public function getHostName($url) {
		$parsed_url = parse_url($url);
		if (!isset($parsed_url['host'])) {
			throw new Runner_Exception_Runner("Invalid site url {$url}");
		}
		return $parsed_url['host'];
	}

	/**
	 * 
	 * @param string $optionName
	 * @return mix
	 * @throws Runner_Exception_Runner
	 */
	public function getOption($optionName) {
		if (!isset($this->_options[$optionName])) {
			throw new Runner_Exception_Runner("Unknown option {$option}");
		}
		return $this->_options[$optionName];
	}

	/**
	 * Set options array
	 * 
	 * @param array $options Options (see $_options description)
	 * @return Rediska_Options
	 */
	public function setOptions(array $options) {
		foreach ($options as $name => $value) {
			$this->setOption($name, $value);
		}

		return $this;
	}

	/**
	 * Get associative array of options
	 *
	 * @return array
	 */
	public function getOptions() {
		return $this->_options;
	}

	/**
	 * Set option
	 * 
	 * @param string $name Name of option
	 * @param mixed $value Value of option
	 * @return Rediska_Options
	 */
	public function setOption($name, $value) {
		if (method_exists($this, "set$name")) {
			return call_user_func(array($this, "set$name"), $value);
		} else if (array_key_exists($name, $this->_options)) {
			$this->_options[$name] = $value;
			return $this;
		} else {
			throw new $this->_optionsException("Unknown option '$name'");
		}
	}

	/**
	 * Init main items
	 */
	protected function _init() {
		$crawlerAdapterName = $this->getOption('crawlerAdapterName');
		$crawlerAdapter = new $crawlerAdapterName;
		$this->_crawler = new Runner_Crawler($crawlerAdapter);

		$parserAdapterName = $this->getOption('parserAdapterName');
		$parserAdapter = new $parserAdapterName;
		$this->_parser = new Runner_Parser($parserAdapter);

		$processorName = $this->getOption('processorName');
		$processor = new $processorName;
		$this->_processor = $processor;

		$this->_planner = new Runner_Planner($this);
	}

}