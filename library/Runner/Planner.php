<?php

class Runner_Planner {

	/**
	 * 	List of processed url hashes
	 * @var array 
	 */
	protected $_processedList = array();

	/**
	 *  Planner queue 
	 * 	key => url hash, value => url
	 * @var array 
	 */
	protected $_plannerQueue = array();

	/**
	 *
	 * @var Runner
	 */
	protected $_runner;

	/**
	 * 
	 * @param Runner $runner
	 */
	public function __construct(Runner $runner) {
		$this->_runner = $runner;
		$this->addUrl($runner->getOption('siteUrl'));
	}

	/**
	 * Add url to Planner Queue
	 * @param string $url
	 */
	public function addUrl($url) {
		$url = $this->_normalizeUrl($url);
		if ($this->_shouldBeAdded($url)) {
			$this->_addToPlannerQueue($url);
		}
	}

	/**
	 * Butch add urls from array 
	 * @param array $urls
	 */
	public function addUrls(Array $urls) {
		foreach ($urls as $url) {
			$this->addUrl($url);
		}
	}

	/**
	 * Move url from planner queue to processed list
	 * @param string $url
	 * @throws Runner_Exception_Runner]
	 */
	public function moveToProcessed($url) {
		$url = $this->_normalizeUrl($url);
		if (!$this->_isInPlannerQueue($url)) {
			throw new Runner_Exception_Runner("Url {$url} not in Planner queue");
		}
		if ($this->_isInProcessedList($url)) {
			throw new Runner_Exception_Runner("Url {$url} alredy added to Processed List");
		}
		$this->_removeFromPlannerQueue($url);
		$this->_addToProcessedList($url);
	}

	/**
	 * Get next url from planner queue
	 * @return string
	 */
	public function getNext() {
		return reset($this->_plannerQueue);
	}

	/**
	 * Get planner queue size
	 * @return integer
	 */
	public function plannerQueueSize() {
		return count($this->_plannerQueue);
	}

	/**
	 * Check is exists unprocessed urls
	 * @return bool
	 */
	public function plannerQueueHasMore() {
		return $this->plannerQueueSize() > 0;
	}

	/**
	 * Get processed list size
	 * @return integer
	 */
	public function processedListSize() {
		return count($this->_processedList);
	}

	/**
	 * Get processed list
	 * @return array
	 */
	public function getProcessedList() {
		return $this->_processedList;
	}

	/**
	 * 
	 * @return type
	 */
	public function getPlannerQueue() {
		return $this->_plannerQueue;
	}

	/**
	 * Remove url from planner queue
	 * @param string $url
	 */
	protected function _removeFromPlannerQueue($url) {
		$hash = $this->_getHash($url);
		unset($this->_plannerQueue[$hash]);
	}

	/**
	 * add url to processed list
	 * @param string $url
	 */
	protected function _addToProcessedList($url) {
		$hash = $this->_getHash($url);
		$this->_processedList[] = $hash;
	}

	/**
	 * Add url to planner queue
	 * @param string $url
	 */
	protected function _addToPlannerQueue($url) {
		$hash = $this->_getHash($url);
		$this->_plannerQueue[$hash] = $url;
	}

	/**
	 * Check url is internal
	 * @param string $url
	 * @return bool
	 */
	protected function _isInternal($url) {
		$runnerHostName = $this->_runner->getSiteHostName();
		$urlHostName = $this->_runner->getHostName($url);
		return ($runnerHostName == $urlHostName);
	}

	/**
	 * Check is url in processed list
	 * @param string $url
	 * @return bool
	 */
	protected function _isInProcessedList($url) {
		$hash = $this->_getHash($url);
		return in_array($hash, $this->_processedList);
	}

	/**
	 * Check is url in processed list
	 * @param string $url
	 * @return bool
	 */
	protected function _isInPlannerQueue($url) {
		$hash = $this->_getHash($url);
		return array_key_exists($hash, $this->_plannerQueue);
	}

	/**
	 * Agregate is should be url added checkings
	 * @param string $url
	 * @return bool
	 */
	protected function _shouldBeAdded($url) {
		return (!$this->_isInProcessedList($url) && !$this->_isInPlannerQueue($url) && $this->_isInternal($url));
	}

	/**
	 * Get url hash
	 * @param string $url
	 * @return string
	 */
	protected function _getHash($url) {
		return md5($url);
	}

	/**
	 * Normalize url
	 * @todo Refactor
	 * @param string $url
	 * @return string
	 */
	protected function _normalizeUrl($url) {
		if (!parse_url($url, PHP_URL_HOST)) {

			$siteUrl = $this->_runner->getOption('siteUrl');
			$prefix = parse_url(rtrim($siteUrl, "/"), PHP_URL_HOST);

			if (strpos($prefix, $url) === false) {
				$url = $prefix . $url;
			}
			if (!strpos('http', $url)) {
				$url = 'http://' . $url;
			}
		}
		$url = rtrim($url, "/");
		return $url;
	}

}