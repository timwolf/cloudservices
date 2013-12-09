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

// Insert the path where you unpacked log4php
include($base_dir . 'vendor/log4php/src/main/php/Logger.php');

// Tell log4php to use our configuration file.
//Logger::configure('../vendor/log4php/config.xml');
Logger::configure($base_dir . 'vendor/log4php/config.php');


/**
 * This is a classic usage pattern: one logger object per class.
 */
class SugarLogger
{
    /** Holds the Logger. */
    private $log;

    /** Logger is instantiated in the constructor. */
    public function __construct()
    {
        // The __CLASS__ constant holds the class name, in our case "Foo".
        // Therefore this creates a logger named "Foo" (which we configured in the config file)
        $this->log = Logger::getLogger(__CLASS__);
    }

    /** Logger can be used from any member method. */
    public function trace($message)
    {
        $this->log->trace($message);
    }

    public function debug($message)
    {
        $this->log->debug($message);
    }

    public function info($message)
    {
        $this->log->info($message);
    }

    public function warn($message)
    {
        $this->log->warn($message);
    }

    public function error($message)
    {
        $this->log->error($message);
    }

    public function fatal($message)
    {
        $this->log->fatal($message);
    }

}

$GLOBALS['log'] = new SugarLogger();
