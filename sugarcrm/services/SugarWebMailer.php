<?php

class SugarWebMailer extends SugarServiceApi
{

    public function registerApiRest()
    {
        $api = array(
            'sendMail' => array(
                'reqType' => 'POST',
                'path' => array('webmail', 'send'),
                'pathVars' => array('', ''),
                'method' => 'sendMail',
            ),
            'getSendRequestStatus' => array(
                'reqType' => 'GET',
                'path' => array('webmail', 'status', '?'),
                'pathVars' => array('', '', 'request_id'),
                'method' => 'getSendRequestStatus',
            ),
            'listSendRequestStatus' => array(
                'reqType' => 'POST',
                'path' => array('webmail', 'status'),
                'pathVars' => array('', ''),
                'method' => 'listSendRequestStatus',
            ),
        );

        return $api;
    }

    public function sendMail($params)
    {
        printf("\n\nGET .... Hey - I am in (webmail/sendMail) SugarWebMailer - in Method: sendMail\n");
        printf("Number of params: %d\n", count($params));
        print_r($params);

        printf("\n\n--- SAMPLE ----\n");
        $this->db_sample();
    }

    public function getSendRequestStatus($params)
    {
        printf(
            "\n\nGET .... Hey - I am in (webmail/status/{request_id}) SugarWebMailer - in Method: getSendRequestStatus\n"
        );
        printf("Number of params: %d\n", count($params));
        print_r($params);

        printf("\n\n--- SAMPLE ----\n");
        $this->db_sample();
    }

    public function listSendRequestStatus($params)
    {
        printf("\n\nGET .... Hey - I am in (webmail/status) SugarWebMailer - in Method: listSendRequestStatus\n");
        printf("Number of params: %d\n", count($params));
        print_r($params);

        printf("\n\n--- SAMPLE ----\n");
        $this->db_sample();
    }


    private function db_sample()
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

}
