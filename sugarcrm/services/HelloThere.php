<?php

class HelloThere extends SugarServiceApi
{

    function __construct()
    {
        // echo "... POOF!\n\n";
    }

    public function registerApiRest()
    {
        $api = array(
            'First_Get_Definition'   => array(
                'reqType'  => 'GET',
                'path'     => array('hello', '?', 'bugs', '?'),
                'pathVars' => array('', 'user_id', '', 'bug_id'),
                'method'   => 'firstGetMethod',
            ),
            'Second_Get_Definition'  => array(
                'reqType'  => 'GET',
                'path'     => array('hello','recipients', '?'),
                'pathVars' => array('', '', 'recipient_id'),
                'method'   => 'secondGetMethod',
            ),
            'First_Post_Definition'  => array(
                'reqType'   => 'POST',
                'path'      => array('hello'),
                'pathVars'  => array(''),
                'method'    => 'firstPostMethod',
            ),
            'Second_Post_Definition' => array(
                'reqType'   => 'POST',
                'path'      => array('qwerty', 'recipients'),
                'pathVars'  => array('', ''),
                'method'    => 'secondPostMethod',
            ),
            'Third_Post_Definition' => array(
                'reqType'   => 'POST',
                'path'     => array('hello','recipients', '?'),
                'pathVars' => array('', '', 'recipient_id'),
                'method'    => 'thirdPostMethod',
            ),
        );

        return $api;
    }

    public function firstGetMethod($params)
    {
        $result = array(
            "req"    => 'in HelloThere - in Method: firstGetMethod',
            "params" => $params,
            "data"   => $this->db_sample_1()
        );
       return $result;
    }


    public function secondGetMethod($params)
    {
        $result = array(
            "req"    => 'in HelloThere - in Method: secondGetMethod',
            "params" => $params,
            "data"   => $this->db_sample_2()
        );
        return $result;
    }

    public function firstPostMethod($params)
    {
        $result = array(
            "req"    => 'in HelloThere - in Method: firstPostMethod',
            "params" => $params,
            "data"   => $this->db_sample_1()
        );
        return $result;
    }

    public function secondPostMethod($params)
    {
        $result = array(
            "req"    => 'in HelloThere - in Method: secondPostMethod',
            "params" => $params,
            "data"   => $this->db_sample_2()
        );
        return $result;
    }

    public function thirdPostMethod($params)
    {
        $result = array(
            "req"    => 'in HelloThere - in Method: thirdPostMethod',
            "params" => $params,
            "data"   => $this->db_sample_1()
        );
        return $result;
    }


    private function db_sample_1()
    {
        $db   = $this->db;
        $rows = array();

        $sql = "SELECT * from contacts";
        $sql .= " WHERE id != 'baloney'";
        $sql .= " ORDER BY id LIMIT 1";
        $result = mysql_query($sql, $db);
        if ($result) {
            while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $rows[] = $row;
            }
            mysql_free_result($result);
            //printf("ROWS=%d\n",count($rows));
        }
        return $rows;
    }


    private function db_sample_2()
    {
        $db   = $this->db;
        $rows = array();

        $sql = "SELECT * from contacts";
        $sql .= " WHERE id != 'baloney'";
        $sql .= " ORDER BY id LIMIT 5";
        $result = mysql_query($sql, $db);
        if ($result) {
            while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $rows[] = $row;
            }
            mysql_free_result($result);
            //printf("ROWS=%d\n",count($rows));
        }
        return $rows;
    }
}
