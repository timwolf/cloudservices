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

require_once $base_dir . 'util/utils.php';
require_once $base_dir . 'model/MailServiceSendParameters.php';


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
     * @param string $api_user required
     * @param string $api_pass required
     */
    function setServiceAccountInfo($api_user, $api_pass);

    /**
     * @abstract
     * @access public
     * @param string $customer_id required
     * @param MailServiceSendParameters $sendParams required
     */
    function send($customer_id, MailServiceSendParameters $sendParams);
}
