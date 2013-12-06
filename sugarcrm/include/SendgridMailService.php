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

require_once 'iMailService.php';
require_once '../vendor/sendgrid/unirest-php/lib/Unirest.php';
require_once '../vendor/sendgrid/sendgrid-php/lib/SendGrid.php';
SendGrid::register_autoloader();

class SendgridMailService implements iMailService
{
    private static $account_user = 'twolf';
    private static $account_pass = 'tjw998468';

    function __construct() {

    }

    public function send(MailServiceSendParameters $sendParams) {
        $attachments = array();
        $exception = null;

        $sendgrid = new SendGrid(self::$account_user, self::$account_pass);

        $mail = new SendGrid\Email();

        /**
        $global_merge_vars = array(
            array(
                'name' => 'company_name',
                'content' => 'BakersField Electronics, Inc.',
            ),
            array(
                'name' => 'service_provider',
                'content' => 'Sendgrid',
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
            array("abc@yahoo.com", "Captain Kangaroo",   "merge-data" => array("Captain",   "Kangaroo",     "Chicago", 		"Illinois",		"10/24/2014",	"9:15 AM",	"Robert Blake",		"Robert")),
            array("abc@yahoo.com", "Doctor Do Little",   "merge-data" => array("Doctor",    "Do Little",    "Milwaukee", 	"Wisconsin",	 "8/12/2014",	"8:10 AM",	"Peter Jennings",	"Peter")),
            array("abc@yahoo.com", "Casper the Ghost",   "merge-data" => array("Casper",    "Ghost",        "Indianapolis", "Indiana",		 "7/24/2014",	"10:30 AM", "Roger Rabbit", 	"Roger")),
            array("abc@yahoo.com", "Curly Howard",       "merge-data" => array("Curly",     "Howard",       "Minneapolis", 	"Minnesota",	 "9/3/2014",	"10:25 AM", "Clark Kent",		"Clark")),
            array("abc@yahoo.com", "Moe Howard",         "merge-data" => array("Moe",       "Howard",       "St. Paul", 	"Minnesota",	"11/16/2014",	"2:25 PM",	"Bruce Willis", 	"Bruce")),
            array("abc@yahoo.com", "Larry Fine",         "merge-data" => array("Larry",     "Fine",         "Rochester", 	"Minnesota",	"12/25/2014",	"5:15 PM",	"David Banner", 	"David")),
        );
        **/

        /*-- Merge field delimeters present in the provided HTML --*/
        $global_merge_data       = $sendParams->global_merge_data;
        $merge_field_delimeters  = $sendParams->merge_field_delimeters;
        $recipient_merge_vars    = $sendParams->recipient_merge_vars;
        $recipients              = $sendParams->recipients;

        $num_global_merge_vars    = count($global_merge_data);
        $num_recipient_merge_vars = count($recipient_merge_vars);

        // addSubstitution("%name%", array("John", "Harry", "Bob"));

        $begin_delimeter = empty($merge_field_delimeters['begin']) ? '' : $merge_field_delimeters['begin'];
        $end_delimeter   = empty($merge_field_delimeters['end']) ? '' : $merge_field_delimeters['end'];

        if ($num_global_merge_vars > 0) {
            $global_merge_vars = array();
            $global_merge_value = array();
            $global_merge_var_data = array();
            $i=0;
            foreach($global_merge_data as $gdata) {
                $global_merge_vars[$i]  = $begin_delimeter . $gdata['name'] . $end_delimeter;
                $global_merge_value[$i] = $gdata['content'];
                $global_merge_var_data[$i] = array();
                $i++;
            }
        }

        if ($num_recipient_merge_vars > 0) {
            $merge_vars = array();
            $merge_var_data = array();
            $i=0;
            foreach($recipient_merge_vars as $var) {
                $merge_vars[$i] = $begin_delimeter . $var . $end_delimeter;
                $merge_var_data[$i] = array();
                $i++;
            }
        }

        $toList = array();
        foreach($recipients AS $recipient) {
            if (!empty($recipient['email'])) {
                $email = $recipient['email'];
                $name  = empty($recipient['name']) ? '' : $recipient['name'];
                $toList[] = $email;
                if ($num_global_merge_vars > 0) {
                    $i=0;
                    foreach($global_merge_data as $gdata) {
                        $global_merge_var_data[$i][] = $global_merge_value[$i];
                        $i++;
                    }
                }
                if ($num_recipient_merge_vars > 0) {
                    $i=0;
                    $mdata = empty($recipient['merge-data']) ? array() : $recipient['merge-data']; // Supplied in recipient
                    $mcount = count($mdata);
                    foreach($recipient_merge_vars AS $var) {
                        $value = ($mcount > $i) ? $mdata[$i] : '';
                        $merge_var_data[$i][] = $value;
                        $i++;
                    }
                }
            }
        }

        try {
            //print_r($toList);

            $mail->setTos($toList);
            if ($num_global_merge_vars > 0) {
                $i=0;
                foreach($global_merge_vars as $var) {
                    $mail->addSubstitution($global_merge_vars[$i], $global_merge_var_data[$i]);
                     //printf("Substitution-Var: %s\n",$global_merge_vars[$i]);
                     //print_r($global_merge_var_data[$i]);
                    $i++;
                }
            }
            if ($num_recipient_merge_vars > 0) {
                $i=0;
                foreach($recipient_merge_vars as $var) {
                    $mail->addSubstitution($merge_vars[$i], $merge_var_data[$i]);
                     //printf("Substitution-Var: %s\n",$merge_vars[$i]);
                     //print_r($merge_var_data[$i]);
                    $i++;
                }
            }

            $mail->setFrom($sendParams->from_email);

            $mail->setSubject($sendParams->subject);

            $mail->setText($sendParams->text_body);
            $mail->setHtml($sendParams->html_body);

            $mail->setMessageHeaders($sendParams->x_headers);

            if (!empty($sendParams->attachments)) {
                $base_dir = dirname(__FILE__) . "/../temp";
                if (!file_exists($base_dir)) {
                    mkdir($base_dir, 0777);
                }

                foreach($sendParams->attachments as $paramAttachment) {
                    $tempfile = $base_dir . "/" . create_guid();
                    $fp = fopen($tempfile, 'w');
                    fwrite($fp, base64_decode($paramAttachment['content']));
                    fclose($fp);

                    $attachments[$paramAttachment['name']] = $tempfile;
                }

               if (!empty($attachments)) {
                  $mail->setAttachments($attachments);
               }
            }

            $result = $sendgrid->web->send($mail);
        } catch(Exception $e) {

            $exception = $e;

        }

        if (!empty($attachments)) {
            foreach($attachments as $filename => $tempfile) {
                @unlink($tempfile);
            }
        }

        if (!empty($exception)) {
            throw new SugarApiException($exception->getMessage());
        }

        return($result);
    }
}
