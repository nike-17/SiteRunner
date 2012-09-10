<?php
include_once  './../library/Runner.php';
$options = array(
	'siteUrl' => 'http://www.zalora.sg/'
);
$runner = new Runner($options);
var_dump($runner);die();