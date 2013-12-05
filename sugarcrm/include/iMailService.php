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

require_once "common.php";
require_once "commonsql.php";
require_once "util.php";
require_once "MailServiceSendParameters.php";


/**
 * This defines the basic interface required by the Sugar Cloud Services to perform Mail functionality
 * via a Third Party Web Service.
 *
 * @interface
 */
interface IMailService
{
    /**
     * @abstract
     * @access public
     * @param MailServiceSendParameters $sendParams required
     */
    public function send(MailServiceSendParameters $sendParams);

}
