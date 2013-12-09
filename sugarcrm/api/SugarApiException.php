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

class SugarApiException extends Exception
{
    public $httpCode = 400;
    public $message = '';

    /**
     * Extra data attached to the exception
     * @var array
     */
    public $extraData = array();

    /**
     * @param string $messageLabel optional Label for error message.  Used to load the appropriate translated message.
     * @param array $msgArgs optional set of arguments to substitute into error message string
     * @param string|null $moduleName Provide module name if $messageLabel is a module string, leave empty if
     *  $messageLabel is in app strings.
     * @param int $httpCode
     * @param string $errorLabel
     */
    function __construct($message = null, $httpCode = null)
    {
        if (!empty($httpCode)) {
            $this->httpCode = $httpCode;
        }

        $this->message = $message;
        parent::__construct($this->message);
    }


    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * Set exception extra data
     * @param string $key
     * @param mixed $data
     * @return SugarApiException
     */
    public function setExtraData($key, $data)
    {
        $this->extraData[$key] = $data;
        return $this;
    }

}
/**
 * General error, no specific cause known.
 */
class SugarApiExceptionError extends SugarApiException
{
    public $httpCode = 500;
}

/**
 * Incorrect API version
 */
class SugarApiExceptionIncorrectVersion extends SugarApiException
{
    public $httpCode = 301;
}

/**
 * Token not supplied or token supplied is invalid.
 * The client should display the username and password screen
 */
class SugarApiExceptionNeedLogin extends SugarApiException
{
    public $httpCode = 401;
}

/**
 * The user's session is invalid
 * The client should get a new token and retry.
 */
class SugarApiExceptionInvalidGrant extends SugarApiException
{
    public $httpCode = 401;
}

/**
 * This action is not allowed for this user.
 */
class SugarApiExceptionNotAuthorized extends SugarApiException
{
    public $httpCode = 403;
}
/**
 * This user is not active.
 */
class SugarApiExceptionPortalUserInactive extends SugarApiException
{
    public $httpCode = 403;
}
/**
 * Portal is not activated by configuration.
 */
class SugarApiExceptionPortalNotConfigured extends SugarApiException
{
    public $httpCode = 403;
}
/**
 * URL does not resolve into a valid REST API method.
 */
class SugarApiExceptionNoMethod extends SugarApiException
{
    public $httpCode = 404;
    public $errorLabel = 'no_method';
}
/**
 * Resource specified by the URL does not exist.
 */
class SugarApiExceptionNotFound extends SugarApiException
{
    public $httpCode = 404;
}
/**
 * Thrown when the client attempts to edit the data on the server that was already edited by
 * different client.
 */
class SugarApiExceptionEditConflict extends SugarApiException
{
    public $httpCode = 409;
}

class SugarApiExceptionInvalidHash extends SugarApiException
{
    public $httpCode = 412;
}

class SugarApiExceptionRequestTooLarge extends SugarApiException
{
    public $httpCode = 413;
}
/**
 * One of the required parameters for the request is missing.
 */
class SugarApiExceptionMissingParameter extends SugarApiException
{
    public $httpCode = 422;
}
/**
 * One of the required parameters for the request is incorrect.
 */
class SugarApiExceptionInvalidParameter extends SugarApiException
{
    public $httpCode = 422;
}
/**
 * The API method is unable to process parameters due to some of them being wrong.
 */
class SugarApiExceptionRequestMethodFailure extends SugarApiException
{
    public $httpCode = 424;
}

/**
 * The client is out of date for this version
 */
class SugarApiExceptionClientOutdated extends SugarApiException
{
    public $httpCode = 433;
}

/**
 * We're in the maintenance mode
 */
class SugarApiExceptionMaintenance extends SugarApiException
{
    public $httpCode = 503;
}
