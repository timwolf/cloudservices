<?php 

$debug = false; 
$trace = true;

$sugarcrm_rootdir = dirname(__FILE__) . '/../';

require_once($sugarcrm_rootdir . "util/common.php");
require_once($sugarcrm_rootdir . "util/commonsql.php");
require_once($sugarcrm_rootdir . "util/util.php");

require_once($sugarcrm_rootdir . "model/JobQueue.php");
require_once($sugarcrm_rootdir . "model/JobTask.php");

$db = getServiceDatabaseConnection();
       
$max_interval_seconds = 285;   // 4m:45s  
$wait_time_seconds    = 10;

$start_time = time(); 
$end_time   = $start_time + $max_interval_seconds;

$queue = new JobQueue($db);    

$logdir  = $sugarcrm_rootdir . 'log';
$logfile = $logdir . "/cron.log"; 
          
$fp = fopen($logfile, "a");
$msg = sprintf("-- Start JobHandler - Time: %s  Datetime: %s\n", $start_time, date("Y-m-d H:i:s"));
fputs($fp,$msg);
fclose($fp);  

while (true) {
	if (time() >= $end_time) {
		break;
	}
	
	$task = $queue->readQueue();  
	if (!empty($task)) { 
		
		if ($trace) {              
	 		$fp = fopen($logfile, "a");
			$msg = sprintf("Datetime: %s:  Cust-Id: %s  Job-Id: %s  Task-Id: %s  Last: %d\n",  
			   date("Y-m-d H:i:s"), $task->cust_id, $task->job_id, $task->task_id, $task->last);
			fputs($fp,$msg);
			fclose($fp);
		}
		
		if ($debug) {
			printf("----------------------\n");
	   		printf("Cust-Id: %s\n",  $task->cust_id); 
	   		printf("Job-Id:  %s\n",  $task->job_id);
	   		printf("Task-Id: %s\n",  $task->task_id);
	  		printf("Last:    %d\n",  $task->last);
	   		printf("Data:    ");     print_r($task->data);
			// sleep(1); 
		}
	} else {
         
		/*--
        if ($trace) {              
	 		$fp = fopen($logfile, "a");
			$msg = sprintf("-- SLEEP - Time: %s  Datetime: %s\n", $start_time, date("Y-m-d H:i:s"));
			fputs($fp,$msg);
			fclose($fp);
		}
		--*/
		
		sleep($wait_time_seconds);
	}
}             
                    
$end = time();
$tm = $end - time();
$fp = fopen($logfile, "a");
$msg = sprintf("-- Stop JobHandler  - Time: %s  Datetime: %s   ... Elapsed (%s seconds)\n", $end, date("Y-m-d H:i:s"),  $end - $start_time);
fputs($fp,$msg);
fclose($fp);