<?php

class Cli {

	/**
	 * Write Info message
	 * @param String $message
	 */
	public function info($message) {
		fprintf(STDOUT, date('[Y-m-d H:i:s] ') . $message . "\n");
	}

	
	/**
	 * Write Error message
	 * @param String $message
	 */
	public function error($message) {
		fprintf(STDERR, date('[Y-m-d H:i:s] ') . $message . "\n");
	}

}