<?php
require_once("../util/commonsql.php");
require_once("../util/utils.php");

require_once("../model/JobQueue.php");
require_once("../model/JobTask.php");

$db = getServiceDatabaseConnection();

$queue = new JobQueue($db);

$task = $queue->readQueue(); 
     
if (!empty($task)) {
   printf("Cust-Id: %s\n",  $task->cust_id); 
   printf("Job-Id:  %s\n",  $task->job_id);
   printf("Task-Id: %s\n",  $task->task_id);
   printf("Last:    %d\n",  $task->last);
   printf("Data:    ");  print_r($task->data);
} else {
   printf("-- Queue Empty --\n\n");
}
