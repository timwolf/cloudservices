<?php

require_once("JobTask.php");

class JobQueue
{
    private $db;

    const STATUS_PENDING  = "pending";
    const STATUS_ACTIVE   = "active";
    const STATUS_STARTED  = "started";
    const STATUS_COMPLETE = "complete";

    function __construct($db)
    {
        $this->db = $db;
    }

    public function addQueue($cust_id, $job_id, array $data, $last = true)
    {
        $job = $this->retrieveJob($cust_id, $job_id);
        if (!empty($job)) {
            // Job Exists
            if ($job['status'] != self::STATUS_PENDING) {
                return false; // job already exists
            }
            $success = $this->createTask($job_id, $cust_id, $data, $last);
            if (!$success) {
                return false;
            }
        } else {
            // Job Does Not Exist
            $success = $this->createTask($job_id, $cust_id, $data, $last);
            if (!$success) {
                return false;
            }
            $initial_status = $last ? self::STATUS_ACTIVE : self::STATUS_PENDING;
            if (!$this->createJob($cust_id, $job_id, $initial_status)) {
                $this->deleteTask($job_id);
                return false;
            }
        }
        if ($last) {
            $this->activateJob($cust_id, $job_id);
        }
        return true;
    }

    public function readQueue($job_id = null)
    {
        // Normally there would be no reason for a generic TaskHandler to target a specific
        // job unless there were a policy to prioritize tasks by job in a multi-task job
        $task = $this->dequeueNextTask($job_id);
        return $task;
    }

    public function deleteQueue($cust_id, $job_id)
    {
        $this->deleteJob($cust_id, $job_id);
    }


    //-----------------------------------------------
    //  Private Functions
    //-----------------------------------------------

    private function createJob($cust_id, $job_id, $initial_status = self::STATUS_ACTIVE)
    {
        $date_entered = gmdate("Y-m-d H:i:s");
        $date_modified = $date_entered;
        $sql = "INSERT into jobqueue VALUES('$job_id', '$cust_id', '$initial_status', 'NULL', 0, '$date_entered', '$date_modified')";
        $result = mysql_query($sql, $this->db);
        if (($result) && (mysql_affected_rows() == 1)) {
            return true;
        }
        return false;
    }

    private function deleteJob($cust_id, $job_id)
    {
        $sql = "DELETE FROM jobqueue";
        $sql .= " WHERE cust_id  = '" . $cust_id . "'";
        $sql .= " AND   job_id   = '" . $job_id . "'";
        mysql_query($sql, $this->db);

        $sql = "DELETE FROM taskqueue";
        $sql .= " WHERE job_id   = '" . $job_id . "'";
        mysql_query($sql, $this->db);
    }

    private function retrieveJob($cust_id, $job_id)
    {
        $sql = "SELECT * FROM jobqueue";
        $sql .= " WHERE cust_id = '" . $cust_id . "'";
        $sql .= " AND   job_id  = '" . $job_id . "'";
        $sql .= " AND   deleted = 0";
        $result = mysql_query($sql, $this->db);
        if ($result && ($row = mysql_fetch_array($result, MYSQL_ASSOC))) {
            return ($row);
        }
        return false;
    }

    private function activateJob($cust_id, $job_id)
    {
        $this->setJobStatus($cust_id, $job_id, self::STATUS_ACTIVE);
        $sql = "UPDATE taskqueue  SET active=1";
        $sql .= " WHERE job_id  = '" . $job_id . "'";
        mysql_query($sql, $this->db);
    }

    private function setJobStatus($cust_id, $job_id, $status)
    {
        $sql = "UPDATE jobqueue SET status = '" . $status . "'";
        $sql .= " WHERE cust_id = '" . $cust_id . "'";
        $sql .= " AND   job_id  = '" . $job_id . "'";
        $sql .= " AND   deleted = 0";
        mysql_query($sql, $this->db);
    }

    private function createTask($job_id, $cust_id, $data, $last)
    {
        $data = base64_encode(serialize($data));
        $sql = "INSERT into taskqueue VALUES('$job_id', '$cust_id', 'NULL', '$last', '$last', '$data')";
        $result = mysql_query($sql, $this->db);
        if (($result) && (mysql_affected_rows() == 1)) {
            return true;
        }
        return false;
    }

    private function deleteTask($job_id, $id = null)
    {
        $sql = "DELETE FROM taskqueue";
        $sql .= " WHERE job_id  = '" . $job_id . "'";
        if (!empty($id)) {
            $sql .= " AND   id  = '" . $id . "'";
        }
        mysql_query($sql, $this->db);
    }

    private function retrieveTask($job_id, $id)
    {
        $sql = "SELECT * FROM taskqueue";
        $sql .= " WHERE job_id  = '" . $job_id . "'";
        $sql .= " AND   active  = 1";
        $sql .= " AND   id  = '" . $id . "'";
        $result = mysql_query($sql, $this->db);
        if ($result && ($row = mysql_fetch_array($result, MYSQL_ASSOC))) {
            return($this->toJobTask($row));
        }
        return false;
    }

    private function dequeueNextTask($job_id = null)
    {
        mysql_query("LOCK TABLES taskqueue WRITE", $this->db);
        $task = false;

        $sql = "SELECT * FROM taskqueue";
        $sql .= " WHERE active  = 1";
        if (!empty($job_id)) {
            $sql .= " AND job_id  = '" . $job_id . "'";
        }
        $sql .= " ORDER by id LIMIT 1";
        $result = mysql_query($sql, $this->db);
        if ($result && ($row = mysql_fetch_array($result, MYSQL_ASSOC))) {
            if (!empty($row['job_id']) && $row['last']!=0) {
                $this->deleteJob($row['cust_id'], $row['job_id']);
            } else {
                $sql = "UPDATE taskqueue SET active = 2";
                $sql .= " WHERE job_id  = '" .  $row['job_id'] . "'";
                $sql .= " AND   id  = '" . $row['id'] . "'";
                mysql_query($sql, $this->db);
            }
            return($this->toJobTask($row));
        }
        mysql_query("UNLOCK TABLES", $this->db);
        return $task;
    }

    private function toJobTask(array $dbRow) {
        $task = new JobTask();
        $task->cust_id = $dbRow['cust_id'];
        $task->job_id  = $dbRow['job_id'];
        $task->task_id = $dbRow['id'];
        $task->last    = $dbRow['last'];
        if (empty($dbRow['data'])) {
            $task->data = array();
        } else {
            $task->data = unserialize(base64_decode($dbRow['data']));
        }
        return $task;
    }
}

