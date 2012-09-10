<?php

class Runner_Parser {

    /**
     *
     * @var Runner_Parser_Adapter_Interface
     */
    protected $_adapter;

    public function __construct(Runner_Parser_Adapter_Interface $adapter) {
        $this->setAdapter($adapter);
    }

    /**
     *
     * @param Runner_Parser_Adapter_Interface $adapter
     */
    public function setAdapter(Runner_Parser_Adapter_Interface $adapter) {
        $this->_adapter = $adapter;
    }

    /**
     * 
     * @return  Runner_Parser_Adapter_Interface 
     */
    public function getAdapter() {
        return $this->_adapter;
    }

}