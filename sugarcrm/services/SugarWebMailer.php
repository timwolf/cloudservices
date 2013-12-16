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
require_once "../util/Config.php";

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
        $mailProvider = Config::getEmailServiceProvider();
        $mailServiceClass = $mailProvider['provider_name'] . 'MailService';
        $mailServiceFile  = '../include/' . $mailServiceClass . '.php';
        if (file_exists($mailServiceFile)) {
            include_once($mailServiceFile);
        } else {
            throw new SugarApiExceptionNotFound('Service handler not found');
        }

        $sendParams = $this->getSendParameters($params);

        $mailService = new $mailServiceClass();
        $mailService->setServiceAccountInfo($mailProvider['account_id'], $mailProvider['account_password']);

        $response =$mailService->send($this->customer_id, $sendParams);

        return $response;
    }


    public function queueMail($params)
    {
        $sendParams = $this->getSendParameters($params);

        require_once("../model/JobQueue.php");
        $queue = new JobQueue($this->db);

        $data = $sendParams->toArray();
        $job_id = create_guid();
        $result = $queue->writeQueue($this->customer_id, $job_id, $data, true);

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
        );
        return $result;
    }


    public function listSendRequestStatus($params)
    {
        $result = array(
            "req"    => 'in (webmail/sendMail) SugarWebMailer - in Method: listSendRequestStatus',
            "params" => $params,
        );
        return $result;
    }


    private function getSendParameters($params)
    {
        $required = array(
            "RECIPIENTS",
            // "FROM-NAME",
            "FROM-EMAIL",
            "SUBJECT",
        );

        foreach($required as $var) {
            if (empty($params[$var])) {
                throw new SugarApiExceptionMissingParameter("Required Field Missing : " . $var);
            }
        }

        $default_merge_field_delimiters = array(
            "begin" => "*|",
            "end"   => "|*",
        );

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
