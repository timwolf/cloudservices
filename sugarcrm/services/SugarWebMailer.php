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
        $response = $this->sendWebMail_Mandrill($params);
        $result = array(
            // "req"    => 'in (webmail/sendMail) SugarWebMailer - in Method: sendMail',
            "params"   => $params,
            "response" => $response,
            //"data"   => $this->db_sample()
        );
        return $result;
    }

    public function getSendRequestStatus($params)
    {
         $result = array(
            "req"    => 'in (webmail/sendMail) SugarWebMailer - in Method: getSendRequestStatus',
            "params" => $params,
            //"guid"   => create_guid(),
            //"data"   => $this->db_sample()
        );
        return $result;
    }

    public function listSendRequestStatus($params)
    {
        $result = array(
            "req"    => 'in (webmail/sendMail) SugarWebMailer - in Method: listSendRequestStatus',
            "params" => $params,
            //"guid"   => create_guid(),
            //"data"   => $this->db_sample()
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

    private function base64_encode_file($filename) {
        $encoded = '';
        if ($filename && ($fh=fopen($filename, "r"))) {
            $bin = fread($fh, filesize($filename));
            $encoded = base64_encode($bin);
            fclose($fh);
        }
        return $encoded;
    }


    private function sendWebMail_Mandrill($params)
    {
        require_once '../vendor/mandrill/src/Mandrill.php';

        $response = array();

        $required = array(
            "API-USER",
            "API-PASS",
            "RECIPIENTS",
            // "FROM-NAME",
            "FROM-EMAIL",
            "SUBJECT",
        );

        foreach($required as $var) {
            if (empty($params[$var])) {
                throw new SugarApiException("Required Field Missing : " . $var);
            }
        }

        $inline_images = array(
            array(
                "cid" => create_guid(),
                "filename" => '~/images/superman.jpg',
                "filetype" =>  'jpeg'
            ),
        );

        $key='7uzEGd38lB9r6XwSQ0ZJpQ';
        $mandrill = new Mandrill($key);

        /*------

        $post_data = array(
            "API-USER"		=> $user,
            "API-PASS"		=> $pass,
            "ACCOUNT-ID"	=> $account_id,
            "CAMPAIGN-ID"	=> $campaign_id,
            "MERGE-FIELD-DELIMETERS" => $merge_field_delimiters,
            "MERGE-FIELD-VARIABLES"  => $merge_field_variables,
            "RECIPIENTS"	=> $recipients,
            "X-HEADERS"		=> $x_headers,
            "FROM-NAME"		=> $from_name,
            "FROM-EMAIL"	=> $from_email,
            "REPLY-TO"		=> $reply_to,
            "SUBJECT"		=> $subject,
            "HTML-BODY"		=> $html,
            "TEXT-BODY"		=> $text,
            "INLINE-IMAGES" => $images,
            "ATTACHMENTS" 	=> $attachments,
        );

        -------*/


        $default_merge_field_delimiters = array(
            "begin" => "*|",
            "end"   => "|*",
        );

        $api_user    = empty($params['API-USER']) ? ''    : $params['API-USER'];
        $api_pass    = empty($params['API-PASS']) ? ''    : $params['API-PASS'];
        $account_id  = empty($params['ACCOUNT-ID']) ? ''  : $params['ACCOUNT-ID'];
        $campaign_id = empty($params['CAMPAIGN-ID']) ? '' : $params['CAMPAIGN-ID'];
        $merge_field_delimeters = empty($params['MERGE-FIELD-DELIMETERS']) ? $default_merge_field_delimiters : $params['MERGE-FIELD-DELIMETERS'];
        $global_merge_data      = empty($params['GLOBAL-MERGE-DATA'])    ? array() : $params['GLOBAL-MERGE-DATA'];
        $recipient_merge_vars   = empty($params['RECIPIENT-MERGE-VARS']) ? array() : $params['RECIPIENT-MERGE-VARS'];
        $recipients  = empty($params['RECIPIENTS']) ? array() : $params['RECIPIENTS'];
        $x_headers   = empty($params['X-HEADERS'])  ? array() : $params['X-HEADERS'];
        $from_name   = empty($params['FROM-NAME'])  ? '' : $params['FROM-NAME'];
        $from_email  = empty($params['FROM-EMAIL']) ? '' : $params['FROM-EMAIL'];
        $reply_to    = empty($params['REPLY-TO'])   ? $from_email : $params['REPLY-TO'];
        $subject     = empty($params['SUBJECT'])    ? '' : $params['SUBJECT'];
        $html_body   = empty($params['HTML-BODY'])  ? '' : $params['HTML-BODY'];
        $text_body   = empty($params['TEXT-BODY'])  ? '' : $params['TEXT-BODY'];
        $images      = empty($params['INLINE-IMAGES']) ? array() : $params['INLINE-IMAGES'];
        $attachments = empty($params['ATTACHMENTS'])   ? array() : $params['ATTACHMENTS'];

        if (empty($x_headers['reply-to'])) {
            $x_headers['reply-to'] = $reply_to;
        }

        $num_recipient_merge_vars = count($recipient_merge_vars);


        /**
            'global_merge_vars' => array(
                    array(
                        'name' => 'COMPANY_NAME',
                        'content' => 'BakersField Electronics, Inc.',
                    ),
                    array(
                        'name' => 'MAIL_SERVICE',
                        'content' => 'Mandrill',
                    ),
            );

            $recipient_merge_vars = array(
                "first_name",
                "last_name",
                "city",
                "state",
                "appointment_date",
                "appointment_time",
                "representative_name",
                "representative_first_name",
            );

            $recipients = array(
                array("abc@yahoo.com", "Captain Kangaroo", 	"MERGE_DATA" => array("Captain","Kangaroo", 	"Chicago", 		"Illinois",		"10/24/2014",	"9:15 AM",	"Robert Blake",		"Robert")),
                array("abc@yahoo.com", "Doctor Do Little", 	"MERGE_DATA" => array("Doctor", "Do Little", 	"Milwaukee", 	"Wisconsin",	"8/12/2014",	"8:10 AM",	"Peter Jennings",	"Peter")),
                array("abc@yahoo.com", "Casper the Ghost", 	"MERGE_DATA" => array("Casper", "Ghost", 		"Indianapolis", "Indiana",		"7/24/2014",	"10:30 AM", "Roger Rabbit", 	"Roger")),
                array("abc@yahoo.com", "Curly Howard", 		"MERGE_DATA" => array("Curly", 	"Howard", 		"Minneapolis", 	"Minnesota",	"9/3/2014",		"10:25 AM", "Clark Kent",		"Clark")),
                array("abc@yahoo.com", "Moe Howard", 		"MERGE_DATA" => array("Moe", 	"Howard", 		"St. Paul", 	"Minnesota",	"11/16/2014",	"2:25 PM",	"Bruce Willis", 	"Bruce")),
                array("abc@yahoo.com", "Larry Fine", 		"MERGE_DATA" => array("Larry", 	"Fine", 		"Rochester", 	"Minnesota",	"12/25/2014",	"5:15 PM",	"David Banner", 	"David")),
            );

         **/

        $toList = array();
        $mergeFields = array();
        foreach($recipients AS $recipient) {
            if (!empty($recipient['email'])) {
                $toList[] = array(
                    'email' => $recipient['email'],
                    'name'  => empty($recipient['name']) ? '' : $recipient['name'],
                    // 'type' => 'to',
                );
                if ($num_recipient_merge_vars > 0) {
                    $mergeFieldData = array();
                    $mergeFieldData['rcpt'] = $recipient['email'];
                    $mdata = empty($recipient['merge-data']) ? array() : $recipient['merge-data']; // Supplied in recipient
                    $j=0;
                    $mcount = count($mdata);
                    foreach($recipient_merge_vars AS $var) {
                        $value = ($mcount > $j) ? $mdata[$j] : '';
                        $mfield = array(
                            'name'    => $var,
                            'content' => $value
                        );
                        $mergeFieldData['vars'][] = $mfield;
                        $j++;
                    }
                    $mergeFields[] = $mergeFieldData;
                }
            }
        }


        $message = array(
             /*---- Mandrill Settings not provided through Web Service API ------*/
            'important' => false,
            'track_opens' => true,
            'track_clicks' => true,
            'auto_text' => null,
            'auto_html' => null,
            'inline_css' => null,
            'url_strip_qs' => null,
            'view_content_link' => null,
            'tracking_domain' => null,
            'signing_domain' => null,
            'return_path_domain' => null,
            'merge' => true,
            'preserve_recipients' => false,  /* important - keeps recipient names off of the to List (not displayed) */

            /*---- Mandrill Settings provided directly or indirectly through Web Service API ------*/
            'bcc_address'   => $from_email,

            'html'          => $html_body,
            'text'          => $text_body,
            'subject'       => $subject,
            'from_email'    => $from_email,
            'from_name'     => $from_name,
            'to'            => $toList,
            'headers'       => $x_headers,
            'global_merge_vars' => $global_merge_data,
            'merge_vars'    => $mergeFields,
            'images'        => $images,
            'attachments'   => $attachments,
        );


          print_r($message);
          //throw new SugarApiException("Quitting");

        $request = array(
            'key' => $key,
            'message' => $message,
            'async' => false,
        );

        $result = $mandrill->messages->send($message);

        return($result);
    }

}
