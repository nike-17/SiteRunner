<?php

class Runner_Crawler {

	/**
	 *
	 * @var Runner_Crawler_Adapter_Interface 
	 */
	protected $_adapter;

	/**
	 * 
	 * @param Runner_Crawler_Adapter_Interface $adapter
	 */
	public function setAdapter(Runner_Crawler_Adapter_Interface $adapter) {
		$this->_adapter = $adapter;
	}

	/**
	 * 
	 * @return  Runner_Crawler_Adapter_Interface 
	 */
	public function getAdapter() {
		return $this->_adapter;
	}

}