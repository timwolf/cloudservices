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

$base_dir = dirname(__FILE__) . "/../";

require_once 'iMailService.php';
require_once $base_dir . 'vendor/mandrill/src/Mandrill.php';

class MandrillMailService implements iMailService
{
    protected $service_account_user;
    protected $service_account_pass;

    public function setServiceAccountInfo($service_account_user, $service_account_pass)
    {
        $this->service_account_user = $service_account_user;
        $this->service_account_pass = $service_account_pass;
    }

    public function send($customer_id, MailServiceSendParameters $sendParams)
    {
        $exception = null;

        /**
        $global_merge_vars = array(
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
        array("abc@yahoo.com", "Captain Kangaroo",   "merge-data" => array("Captain","Kangaroo", 	"Chicago", 		"Illinois",		"10/24/2014",	"9:15 AM",	"Robert Blake",		"Robert")),
        array("abc@yahoo.com", "Doctor Do Little",   "merge-data" => array("Doctor", "Do Little", 	"Milwaukee", 	"Wisconsin",	 "8/12/2014",	"8:10 AM",	"Peter Jennings",	"Peter")),
        array("abc@yahoo.com", "Casper the Ghost",   "merge-data" => array("Casper", "Ghost", 		"Indianapolis", "Indiana",		 "7/24/2014",	"10:30 AM", "Roger Rabbit", 	"Roger")),
        array("abc@yahoo.com", "Curly Howard",       "merge-data" => array("Curly", 	"Howard", 		"Minneapolis", 	"Minnesota",	 "9/3/2014",	"10:25 AM", "Clark Kent",		"Clark")),
        array("abc@yahoo.com", "Moe Howard",         "merge-data" => array("Moe", 	"Howard", 		"St. Paul", 	"Minnesota",	"11/16/2014",	"2:25 PM",	"Bruce Willis", 	"Bruce")),
        array("abc@yahoo.com", "Larry Fine",         "merge-data" => array("Larry", 	"Fine", 		"Rochester", 	"Minnesota",	"12/25/2014",	"5:15 PM",	"David Banner", 	"David")),
        );
         **/

        /*-- Merge field delimeters present in the provided HTML --*/
        $merge_field_delimeters = $sendParams->merge_field_delimeters;
        $recipient_merge_vars = $sendParams->recipient_merge_vars;
        $recipients = $sendParams->recipients;

        $num_recipient_merge_vars = count($recipient_merge_vars);
        $toList = array();
        $mergeFields = array();
        foreach ($recipients AS $recipient) {
            if (!empty($recipient['email'])) {
                $toList[] = array(
                    'email' => $recipient['email'],
                    'name' => empty($recipient['name']) ? '' : $recipient['name'],
                    // 'type' => 'to',
                );
                if ($num_recipient_merge_vars > 0) {
                    $mergeFieldData = array();
                    $mergeFieldData['rcpt'] = $recipient['email'];
                    $mdata = empty($recipient['merge-data']) ? array() : $recipient['merge-data']; // Supplied in recipient
                    $j = 0;
                    $mcount = count($mdata);
                    foreach ($recipient_merge_vars AS $var) {
                        $value = ($mcount > $j) ? $mdata[$j] : '';
                        $mfield = array(
                            'name' => $var,
                            'content' => $value
                        );
                        $mergeFieldData['vars'][] = $mfield;
                        $j++;
                    }
                    $mergeFields[] = $mergeFieldData;
                }
            }
        }

        $mandrill = new Mandrill($this->service_account_user);

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
            'preserve_recipients' => false, /* important - keeps recipient names off of the to List (not displayed) */

            /*---- Mandrill Settings provided directly or indirectly through Web Service API ------*/
            'bcc_address' => $sendParams->from_email,
            'html' => $sendParams->html_body,
            'text' => $sendParams->text_body,
            'subject' => $sendParams->subject,
            'from_email' => $sendParams->from_email,
            'from_name' => $sendParams->from_name,
            'to' => $toList,
            'headers' => $sendParams->x_headers,
            'global_merge_vars' => $sendParams->global_merge_data,
            'merge_vars' => $mergeFields,
            'images' => $sendParams->images,
            'attachments' => $sendParams->attachments,
        );

        try {
            $GLOBALS['log']->debug("-- MESSAGE --\n" . print_r($message, true));
            $result = $mandrill->messages->send($message);
            $GLOBALS['log']->debug("-- RESULT --\n" . print_r($result, true));
        } catch (Exception $e) {
            $exception = $e;
        }

        if (!empty($exception)) {
            throw new SugarApiException($exception->getMessage());
        }

        return ($result);
    }
}
