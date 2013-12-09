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

require_once "../model/MailServiceSendParameters.php";

class SugarWebMailer extends SugarServiceApi
{
    const MAIL_SERVICE_VENDOR = 'Mandrill';   // 'Mandrill, 'Sendgrid', 'Mailjet  ...

    public function registerApiRest()
    {
        $api = array(
            'sendMail' => array(
                'reqType' => 'POST',
                'path' => array('webmail', 'send'),
                'pathVars' => array('', ''),
                'method' => 'sendMail',
            ),
            'queueMail' => array(
                'reqType' => 'POST',
                'path' => array('mailqueue', 'send'),
                'pathVars' => array('', ''),
                'method' => 'queueMail',
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
        $mailServiceClass = self::MAIL_SERVICE_VENDOR . 'MailService';
        $mailServiceFile  = '../include/' . $mailServiceClass . '.php';
        if (file_exists($mailServiceFile)) {
            include_once($mailServiceFile);
        }

        $sendParams = $this->getSendParameters($params);

        $mailService = new $mailServiceClass();
        $response =$mailService->send($sendParams);

        $result = array(
            "vendor"   => self::MAIL_SERVICE_VENDOR,
            // "params"   => $params,
            "response" => $response,
            //"data"   => $this->db_sample()
        );
        return $result;
    }


    public function queueMail($params)
    {
        $sendParams = $this->getSendParameters($params);

        require_once("../model/JobQueue.php");
        $queue = new JobQueue($this->db);

        $cust_id = empty($params['CUSTOMER-ID']) ? '' : $params['CUSTOMER-ID'];
        $data = $sendParams->toArray();
        $job_id = create_guid();
        $result = $queue->addQueue($cust_id, $job_id, $data, true);

        if ($result) {
            return array(
                "status" => "accepted",
                "job_id" => $job_id,
            );
        }

        throw new SugarApiExceptionError('Unable to process request');
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


    private function getSendParameters($params)
    {
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

        /*------

        $post_data = array(
            "API-USER"		=> $user,
            "API-PASS"		=> $pass,
            "CUSTOMER-ID"	=> $customer_id,
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
        $customer_id = empty($params['CUSTOMER-ID']) ? '' : $params['CUSTOMER-ID'];  // Customer Account Id
        $campaign_id = empty($params['CAMPAIGN-ID']) ? '' : $params['CAMPAIGN-ID']; // Campaign Id
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

        if (!empty($campaign_id)) {
            $x_headers['X-CAMPAIGN-ID'] = $campaign_id;
        }
        if (!empty($customer_id)) {
            $x_headers['X-CUSTOMER-ID'] = $customer_id;
        }

        $sendParams = new MailServiceSendParameters();

        $sendParams->html_body   = $html_body;
        $sendParams->text_body   = $text_body;
        $sendParams->subject     = $subject;
        $sendParams->from_email  = $from_email;
        $sendParams->from_name   = $from_name;
        $sendParams->x_headers   = $x_headers;
        $sendParams->merge_field_delimeters = $merge_field_delimeters;
        $sendParams->global_merge_data      = $global_merge_data;
        $sendParams->recipient_merge_vars   = $recipient_merge_vars;
        $sendParams->recipients             = $recipients;
        $sendParams->images      = $images;
        $sendParams->attachments = $attachments;

        return($sendParams);
    }

}
