<?php

// Register autoloader
require_once dirname(__FILE__) . '/Runner/Autoloader.php';
Runner_Autoloader::register();

class Runner extends Runner_Options {

	protected $_siteHost;
        /**
         *
         * @var Runner_Planner
         */
        protected $_planner;
        protected $_crawler;
        protected $_parser;
        protected $_options = array(
		'siteUrl' => ''
	);

	public function __construct(array $options = array()) {
		parent::__construct($options);
                $this->_crawler = new Runner_Crawler(new Runner_Crawler_Adapter_Fgc());
                $this->_parser = new Runner_Parser(new Runner_Parser_Adapter_Xpath());
                $this->_planner = new Runner_Planner($this);
	}

        public function run(){
            while($this->_planner->plannerQueueSize() > 0) {
                $currenUrl = $this->_planner->getNext();
                $data = $this->_crawler->getAdapter()->getHtmlData($currenUrl);
                $urls = $this->_parser->getAdapter()->getUrls($data);
                foreach($urls as $url){
                    $this->_planner->addUrl($url);
                }
                $this->_planner->moveToProcessed($currenUrl);
                echo "Planner Queue Size " . $this->_planner->plannerQueueSize() . "\n";
                echo "Processed ListSize Size " . $this->_planner->processedListSize() . "\n";

            }

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
		return $parsed_url['host'];
	}



    
}