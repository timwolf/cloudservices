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
        printf("\n\nGET .... Hey - I am in HelloThere - in Method: firstGetMethod\n");
        printf("Number of params: %d\n", count($params));
        print_r($params);

        printf("\n\n--- SAMPLE-1 ----\n");
        $this->db_sample_1();

        printf("\n\n--- SAMPLE-2 ----\n");
        $this->db_sample_2();
        printf("\n\n");
    }


    public function secondGetMethod($params)
    {
        printf("\n\nGET .... Hey - I am in HelloThere - in Method: secondGetMethod\n");
        printf("Number of params: %d\n", count($params));
        print_r($params);

        printf("\n\n--- SAMPLE-1 ----\n");
        $this->db_sample_1();

        printf("\n\n--- SAMPLE-2 ----\n");
        $this->db_sample_2();
        printf("\n\n");
    }

    public function firstPostMethod($params)
    {
        printf("\n\nGET .... Hey - I am in HelloThere - in Method: firstPostMethod\n");
        printf("Number of params: %d\n", count($params));
        print_r($params);

        printf("\n\n--- SAMPLE-1 ----\n");
        $this->db_sample_1();

        printf("\n\n--- SAMPLE-2 ----\n");
        $this->db_sample_2();
        printf("\n\n");
    }

    public function secondPostMethod($params)
    {
        printf("\n\nGET .... Hey - I am in HelloThere - in Method: secondPostMethod\n");
        printf("Number of params: %d\n", count($params));
        print_r($params);

        printf("\n\n--- SAMPLE-1 ----\n");
        $this->db_sample_1();

        printf("\n\n--- SAMPLE-2 ----\n");
        $this->db_sample_2();
        printf("\n\n");
    }

    public function thirdPostMethod($params)
    {
        printf("\n\nPOST .... Hey - I am in HelloThere - in Method: thirdPostMethod\n");
        printf("Number of params: %d\n", count($params));
        print_r($params);

        printf("\n\n--- SAMPLE-1 ----\n");
        $this->db_sample_1();

        printf("\n\n--- SAMPLE-2 ----\n");
        $this->db_sample_2();
        printf("\n\n");
    }


    private function db_sample_1()
    {
        $db = $this->db;

        $sql = "SELECT * from contacts";
        $sql .= " WHERE id != 'baloney' LIMIT 1";
        $result = mysql_query($sql, $db);
        if ($result && ($row = mysql_fetch_array($result, MYSQL_ASSOC))) {
            mysql_free_result($result);
            print_r($row);
            return true;
        }
        return false;

    }

    private function db_sample_2()
    {
        $db = $this->db;

        $sql = "SELECT * from contacts";
        $sql .= " WHERE id != 'baloney'";
        $sql .= " ORDER BY id";
        $result = mysql_query($sql, $db);
        if ($result) {
            while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $rows[] = $row;
            }
            mysql_free_result($result);
            printf("ROWS=%d\n",count($rows));
        }
        return false;

    }
}
