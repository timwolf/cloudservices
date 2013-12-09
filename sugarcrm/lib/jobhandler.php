<?php
$debug = false;
$trace = false;

$GLOBALS['logger_file_name'] = "sugarcron.log";

$sugarcrm_rootdir = dirname(__FILE__) . '/../';

require_once($sugarcrm_rootdir . "util/common.php");
require_once($sugarcrm_rootdir . "util/commonsql.php");
require_once($sugarcrm_rootdir . "util/util.php");
require_once($sugarcrm_rootdir . "util/SugarLogger.php");

require_once($sugarcrm_rootdir . "model/JobQueue.php");
require_once($sugarcrm_rootdir . "model/JobTask.php");

$db = getServiceDatabaseConnection();
if (empty($db)) {
    printf("\n\nUnable to connect to Database - Cron JobHandler terminating ...\n");
    exit;
}

$max_interval_seconds = 285; // 4m:45s
$wait_time_seconds = 10;

$start_time = time();
$end_time = $start_time + $max_interval_seconds;

$queue = new JobQueue($db);

$msg = sprintf("-- Start JobHandler - Time: %s  Datetime: %s", $start_time, date("Y-m-d H:i:s"));
$GLOBALS['log']->debug($msg);

while (true) {
    if (time() >= $end_time) {
        break;
    }

    $task = $queue->readQueue();
    if (!empty($task)) {

        if ($trace) {
            $msg = sprintf(
                "Datetime: %s:  Cust-Id: %s  Job-Id: %s  Task-Id: %s  Last: %d",
                date("Y-m-d H:i:s"),
                $task->cust_id,
                $task->job_id,
                $task->task_id,
                $task->last);

            $GLOBALS['log']->debug($msg);
        }

        if ($debug) {
            printf("----------------------\n");
            printf("Cust-Id: %s\n", $task->cust_id);
            printf("Job-Id:  %s\n", $task->job_id);
            printf("Task-Id: %s\n", $task->task_id);
            printf("Last:    %d\n", $task->last);
            printf("Data:    ");
            print_r($task->data);
            // sleep(1);
        }
    } else {

        // $GLOBALS['log']->debug('Sleeping ' . $wait_time_seconds . 'seconds ...');

        sleep($wait_time_seconds);
    }
}

$end = time();
$tm = $end - time();
$msg = sprintf(
    "-- Stop JobHandler  - Time: %s  Datetime: %s   ... Elapsed (%s seconds)",
    $end,
    date("Y-m-d H:i:s"),
    $end - $start_time
);
$GLOBALS['log']->debug($msg);
