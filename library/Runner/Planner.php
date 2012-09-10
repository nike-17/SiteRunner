<?php

class Runner_Planner {

	protected $_processedList = array();
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
	}

	public function addUrl($url) {
		if ($this->_shouldBeAdded($url)) {
			$this->_addToPlannerQueue($url);
		}
	}

	protected function _addToPlannerQueue($url) {
		$hash = $this->getHash($url);
		$this->_planner_queue[$hash] = $url;
	}

	protected function _isInternal($url) {
		$runnerHostName = $this->_runner->getSiteHostName();
		$urlHostName = $this->_runner->getHostName($url);
		return ($runnerHostName == $urlHostName);
	}

	protected function _isInProcessedList($url) {
		$hash = $this->getHash($url);
		return in_array($hash, $this->_processedList);
	}

	protected function _isInPlannerQueue($url) {
		$hash = $this->getHash($url);
		return array_key_exists($hash, $this->_plannerQueue);
	}

	protected function _shouldBeAdded($url) {
		return !$this->_isInProcessedList($url) && !$this->_isInPlannerQueue($url) && $this->_isInternal($url);
	}

	protected function getHash($url) {
		return md5($url);
	}

}