Site Runner 
=============
Common tool to full crawl  websites and analyze content.

RUN EXAMPLE
-------------

	php example.php

OUTPUT
-------------
	[2012-09-11 18:18:22] Processed List Size 0
	[2012-09-11 18:18:22] Planner Queue Size 282
	[2012-09-11 18:18:22] Not in interval url count  0
	[2012-09-11 18:18:33] Processed List Size 10
	[2012-09-11 18:18:33] Planner Queue Size 2068
...
Where 

 * Processed List Size - count of already processed urls
 * Planner Queue Size - size of planner queue
 * Not in interval url count - count of url that  price is not in interval

Core concepts
-------------  
 * easy to extends - you can write your own parser adapter, crawler adapter and processor.

Right now it works not very fast by few reason
 * Default crawler adapter FGT - use file get contents - possible improvement write curl mutiexec adapter
 * Default parser adapter Xpath is quite slow - i use it because it more simple and easy to explain core concept - possible improvement write regexp adapter

Workflow
-------------

 * We add first url from config to PLanner Queue
 * Crawl URl to get content
 * Process url data by Processor to complite useful job
 * Process url data by Crawler to get all url from page
 * Add all url to PLanner Queue
 * Move current url From PLanner Queue to Processed Url List (to not process it twice)

Possible improvement
------------- 
 * add filter layer to filter bad urls(bad http status, etc)
 * get content hash to check content quality   
