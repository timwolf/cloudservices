<?php
/*
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement ("MSA"), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright  2004-2013 SugarCRM Inc.  All rights reserved.
 */

$debug = false;
$trace = false;
$once  = true;

$GLOBALS['logger_file_name'] = "sugarcron.log";

$sugarcrm_rootdir = dirname(__FILE__) . '/../';

require_once($sugarcrm_rootdir . "util/commonsql.php");
require_once($sugarcrm_rootdir . "util/utils.php");
require_once($sugarcrm_rootdir . "util/SugarLogger.php");
require_once($sugarcrm_rootdir . "util/Config.php");

require_once($sugarcrm_rootdir . "model/JobQueue.php");
require_once($sugarcrm_rootdir . "model/JobTask.php");
require_once($sugarcrm_rootdir . "model/MailServiceSendParameters.php");

$db = getServiceDatabaseConnection();
if (empty($db)) {
    $GLOBALS['log']->fatal('Unable to connect to Database - JobHandler terminating ...');
    exit;
}

$mailProvider = Config::getEmailServiceProvider();
$mailServiceClass = $mailProvider['provider_name'] . 'MailService';
$mailServiceFile = $sugarcrm_rootdir . 'include/' . $mailServiceClass . '.php';
if (file_exists($mailServiceFile)) {
    include_once($mailServiceFile);
} else {
    $GLOBALS['log']->fatal('Mail Service File does Not Exist: ' . $mailServiceFile);
    exit;
}

$max_interval_seconds = 285; // 4m:45s
$wait_time_seconds = 10;

$start_time = time();
$end_time = $start_time + $max_interval_seconds;

$queue = new JobQueue($db);

if (true || $trace) {
    $msg = sprintf("-- Start JobHandler - Time: %s  Datetime: %s", $start_time, date("Y-m-d H:i:s"));
    $GLOBALS['log']->debug($msg);
}

while (true) {
    if (time() >= $end_time) {
        break;
    }

    $task = $queue->readQueue();
    if (!empty($task)) {

        $mailService = new $mailServiceClass();
        $mailService->setServiceAccountInfo($mailProvider['account_id'], $mailProvider['account_password']);

        $sendParams = MailServiceSendParameters::fromArray($task->data);
        $response = $mailService->send($task->cust_id, $sendParams);

        if (true || $trace) {
            $msg = sprintf(
                "Datetime: %s:  Cust-Id: %s  Job-Id: %s  Task-Id: %s  Last: %d",
                date("Y-m-d H:i:s"),
                $task->cust_id,
                $task->job_id,
                $task->task_id,
                $task->last
            );

            $GLOBALS['log']->debug(
                "\n----------------------------- QUEUED REQUEST -----------------------------------"
            );
            $GLOBALS['log']->debug("RESPONSE INFO: " . $msg);
            $GLOBALS['log']->debug("RESPONSE DATA: " . print_r($response, true) . "\n\n");
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

        if ($once) {
            break;
        }

    } else {

        // $GLOBALS['log']->debug('Sleeping ' . $wait_time_seconds . 'seconds ...');

        if ($once) {
            break;
        }

        sleep($wait_time_seconds);
    }
}

$end = time();
$elapsed = $end - $start_time;

if (true || $trace) {
    $msg = sprintf(
        "-- Stop JobHandler  - Time: %s  Datetime: %s   ... Elapsed (%s seconds)",
        $end,
        date("Y-m-d H:i:s"),
        $elapsed
    );
    $GLOBALS['log']->debug($msg);
}

if ($once) {
    printf("Done!\n");
}
