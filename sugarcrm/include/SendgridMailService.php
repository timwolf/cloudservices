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

        try {
            foreach($sendParams->toList AS $to) {
                $mail->addTo($to['email']);
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
