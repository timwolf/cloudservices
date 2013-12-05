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
require_once '../vendor/mandrill/src/Mandrill.php';

class MandrillMailService implements iMailService
{
   private static $account_key = '7uzEGd38lB9r6XwSQ0ZJpQ';

   function __construct() {

   }

   public function send(MailServiceSendParameters $sendParams) {
       $exception = null;

       $mandrill = new Mandrill(self::$account_key);

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
           'bcc_address'   => $sendParams->from_email,

           'html'          => $sendParams->html_body,
           'text'          => $sendParams->text_body,
           'subject'       => $sendParams->subject,
           'from_email'    => $sendParams->from_email,
           'from_name'     => $sendParams->from_name,
           'to'            => $sendParams->toList,
           'headers'       => $sendParams->x_headers,
           'global_merge_vars' => $sendParams->global_merge_data,
           'merge_vars'    => $sendParams->mergeFields,
           'images'        => $sendParams->images,
           'attachments'   => $sendParams->attachments,
       );

       try {
            $result = $mandrill->messages->send($message);
       } catch(Exception $e) {
           $exception = $e;
       }

       if (!empty($exception)) {
           throw new SugarApiException($exception->getMessage());
       }

       return($result);
   }
}
