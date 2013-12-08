<?php

require_once("../util/common.php");
require_once("../util/commonsql.php");
require_once("../util/util.php");

require_once("../model/JobQueue.php");
require_once("../model/JobTask.php");

$db = getServiceDatabaseConnection();
       
$max_interval_seconds = 20;
$wait_time_seconds    = 10;

$start_time = time(); 
$end_time   = $start_time + $max_interval_seconds;

$queue = new JobQueue($db);
       
while (true) {
	if (time() >= $end_time) {
		break;
	}
	
	$task = $queue->readQueue();  
	if (!empty($task)) {
		printf("----------------------\n");
   		printf("Cust-Id: %s\n",  $task->cust_id); 
   		printf("Job-Id:  %s\n",  $task->job_id);
   		printf("Task-Id: %s\n",  $task->task_id);
  		printf("Last:    %d\n",  $task->last);
   		printf("Data:    ");     print_r($task->data);
		sleep(1);
	} else {
   		printf("-- Queue Empty --\n"); 
		sleep($wait_time_seconds);
	}
}
printf("-- Done --\n\n");