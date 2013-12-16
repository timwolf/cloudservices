<?php
require_once("../util/commonsql.php");
require_once("../util/utils.php");

require_once("../model/JobQueue.php");

$db = getServiceDatabaseConnection();

$queue = new JobQueue($db);

$cust_id = create_guid();

$data = array(
    'first_name' => 'Tim',
    'last_name' => 'Wolf',
);

$jcount = 1;
if ($argc >= 2) {
	$jcount = (int) $argv[1];
}

for ($i=0; $i<$jcount; $i++) {
	$tcount = (int) mt_rand(1,6);   
	printf("[%s]\n",$tcount);
	$job_id = create_guid();
	printf("\nCUST: %s JOB:%s\n", $cust_id, $job_id); 
	for ($j=0; $j<$tcount-1; $j++) { 
		$result = $queue->writeQueue($cust_id, $job_id, $data, false);
		printf("writeQueue  Result=%d\n", $result);
	}
	$result = $queue->writeQueue($cust_id, $job_id, $data, true);
	printf("writeQueue FINAL Result=%d\n", $result);
}
