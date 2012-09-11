<?php

include_once './library/Runner.php';
include_once './Cli.php';

$cli = new Cli();

$options = array(
	'siteUrl' => 'http://www.zalora.sg',
	'crawlerAdapterName' => 'Fgc', //file_get_contents
	'parserAdapterName' => 'Xpath', // slow but more readable
	'processorName' => 'Zalora_Price' // check Zalora price Inteval
);
try {
	$runner = new Runner($options);
	$notInIntervalUrls = array();

	while ($runner->getPlanner()->plannerQueueHasMore()) {
		$currenUrl = $runner->getPlanner()->getNext();
		$data = $runner->getCrawler()->getAdapter()->getHtmlData($currenUrl);
		$urls = $runner->getParser()->getAdapter()->getUrls($data);
		$runner->getPlanner()->addUrls($urls);

		if (!$runner->getProcessor()->is_price_interval($data, 20, 2000)) {
			$notInIntervalUrls[] = $currenUrl;
			$cli->info("Price of this item not in providing interval: {$currenUrl}");
		}
		if ($runner->getPlanner()->processedListSize() % 10 == 0) {
			$cli->info("Processed List Size {$runner->getPlanner()->processedListSize()}");
			$cli->info("Planner Queue Size {$runner->getPlanner()->plannerQueueSize()}");
			$notInIntervalUrlsCount = count($notInIntervalUrls);
			$cli->info("Not in interval url count  {$notInIntervalUrlsCount}");
		}
		$runner->getPlanner()->moveToProcessed($currenUrl);
	}
	$cli->info('Results:');
	foreach ($notInIntervalUrls as $url) {
		$cli->info($url);
	}
} catch (Exception $e) {
	$cli->error("Error: {$e->getMessage()}");
}
	

