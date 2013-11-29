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
        $result = array(
            "req"    => 'in (webmail/sendMail) SugarWebMailer - in Method: sendMail',
            "params" => $params,
            "guid"   => create_guid(),
            "data"   => $this->db_sample()
        );
        return $result;
    }

    public function getSendRequestStatus($params)
    {
         $result = array(
            "req"    => 'in (webmail/sendMail) SugarWebMailer - in Method: getSendRequestStatus',
            "params" => $params,
            "guid"   => create_guid(),
            "data"   => $this->db_sample()
        );
        return $result;
    }

    public function listSendRequestStatus($params)
    {
        $result = array(
            "req"    => 'in (webmail/sendMail) SugarWebMailer - in Method: listSendRequestStatus',
            "params" => $params,
            "guid"   => create_guid(),
            "data"   => $this->db_sample()
        );
        return $result;
    }


    private function db_sample()
    {
        $db   = $this->db;
        $rows = array();

        $sql = "SELECT * from marketingcampaigns";
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

}
