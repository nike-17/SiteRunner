<?php

class Runner_Planner {

    protected $_processedList = array();
    protected $_plannerQueue = array();
    /**
     *
     * @var integer
     */
    protected $_position = 0;
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

    public function addUrl($url) {
        $url = $this->_normalizeUrl($url);
        if ($this->_shouldBeAdded($url)) { 
            $this->_addToPlannerQueue($url);
        }
    }

    public function moveToProcessed($url) {
        $url = $this->_normalizeUrl($url);
        if (!$this->_isInPlannerQueue($url)) {
            throw new Runner_Exception_Runner("Url {$url} not in Planner queue");
        }
        if ($this->_isInProcessedList($url)) {
            throw new Runner_Exception_Runner("Url {$url} alredy added to Processed List");
        }
        $this->_addToPlannerQueue($url);
    }

    public function getNext() {
        return reset($this->_plannerQueue);
    }

    public function plannerQueueSize() {

        return count($this->_plannerQueue);
    }

    public function processedListSize() {
        return count($this->_processedList);
    }

    protected function _removeFromPlannerQueue($url) {
        $hash = $this->_getHash($url);
        unset($this->_plannerQueue[$hash]);
    }

    protected function addToProcessedList($url) {
        $hash = $this->_getHash($url);
        $this->_processedList[] = $hash;
    }

    protected function _addToPlannerQueue($url) {
        $hash = $this->_getHash($url);
        $this->_plannerQueue[$hash] = $url;
    }

    protected function _isInternal($url) {
        $runnerHostName = $this->_runner->getSiteHostName();
        $urlHostName = $this->_runner->getHostName($url);
        return ($runnerHostName == $urlHostName);
    }

    protected function _isInProcessedList($url) {
        $hash = $this->_getHash($url);
        return in_array($hash, $this->_processedList);
    }

    protected function _isInPlannerQueue($url) {
        $hash = $this->_getHash($url);
        return array_key_exists($hash, $this->_plannerQueue);
    }

    protected function _shouldBeAdded($url) {
        return (!$this->_isInProcessedList($url) && !$this->_isInPlannerQueue($url) && $this->_isInternal($url));
    }

    protected function _getHash($url) {
        return md5($url);
    }

    protected function _normalizeUrl($url) {
        if (!parse_url($url, PHP_URL_HOST)) {
            $url = $this->_runner->getOption('siteUrl') . $url;
        }
        return $url;
    }

}