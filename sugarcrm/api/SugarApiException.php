<?php
/********************************************************************************
 *The contents of this file are subject to the SugarCRM Professional End User License Agreement
 *("License") which can be viewed at http://www.sugarcrm.com/EULA.
 *By installing or using this file, You have unconditionally agreed to the terms and conditions of the License, and You may
 *not use this file except in compliance with the License. Under the terms of the license, You
 *shall not, among other things: 1) sublicense, resell, rent, lease, redistribute, assign or
 *otherwise transfer Your rights to the Software, and 2) use the Software for timesharing or
 *service bureau purposes such as hosting the Software for commercial gain and/or for the benefit
 *of a third party.  Use of the Software may be subject to applicable fees and any use of the
 *Software without first paying applicable fees is strictly prohibited.  You do not have the
 *right to remove SugarCRM copyrights from the source code or user interface.
 * All copies of the Covered Code must include on each user interface screen:
 * (i) the "Powered by SugarCRM" logo and
 * (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for requirements.
 *Your Warranty, Limitations of liability and Indemnity are expressly stated in the License.  Please refer
 *to the License for the specific language governing these rights and limitations under the License.
 *Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.; All Rights Reserved.
 ********************************************************************************/

require_once 'SugarException.php';

class SugarApiException extends SugarException
{
    public $httpCode = 400;

    /**
     * @param string $messageLabel optional Label for error message.  Used to load the appropriate translated message.
     * @param array $msgArgs optional set of arguments to substitute into error message string
     * @param string|null $moduleName Provide module name if $messageLabel is a module string, leave empty if
     *  $messageLabel is in app strings.
     * @param int $httpCode
     * @param string $errorLabel
     */
    public function __construct($messageLabel = null, $msgArgs = null, $moduleName = null, $httpCode = 0, $errorLabel = null)
    {

        if ($httpCode != 0) {
            $this->httpCode = $httpCode;
        }
        parent::__construct($messageLabel, $msgArgs, $moduleName, $errorLabel);
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }
}
/**
 * General error, no specific cause known.
 */
class SugarApiExceptionError extends SugarApiException
{
    public $httpCode = 500;
    public $errorLabel = 'fatal_error';
    public $messageLabel = 'EXCEPTION_FATAL_ERROR';
}

/**
 * Incorrect API version
 */
class SugarApiExceptionIncorrectVersion extends SugarApiException
{
    public $httpCode = 301;
    public $errorLabel = 'incorrect_version';
    public $messageLabel = 'EXCEPTION_INCORRECT_VERSION';
}

/**
 * Token not supplied or token supplied is invalid.
 * The client should display the username and password screen
 */
class SugarApiExceptionNeedLogin extends SugarApiException
{
    public $httpCode = 401;
    public $errorLabel = 'need_login';
    public $messageLabel = 'EXCEPTION_NEED_LOGIN';
}

/**
 * The user's session is invalid
 * The client should get a new token and retry.
 */
class SugarApiExceptionInvalidGrant extends SugarApiException
{
    public $httpCode = 401;
    public $errorLabel = 'invalid_grant';
    public $messageLabel = 'EXCEPTION_INVALID_TOKEN';
}

/**
 * This action is not allowed for this user.
 */
class SugarApiExceptionNotAuthorized extends SugarApiException
{
    public $httpCode = 403;
    public $errorLabel = 'not_authorized';
    public $messageLabel = 'EXCEPTION_NOT_AUTHORIZED';
}
/**
 * This user is not active.
 */
class SugarApiExceptionPortalUserInactive extends SugarApiException
{
    public $httpCode = 403;
    public $errorLabel = 'inactive_portal_user';
    public $messageLabel = 'EXCEPTION_INACTIVE_PORTAL_USER';
}
/**
 * Portal is not activated by configuration.
 */
class SugarApiExceptionPortalNotConfigured extends SugarApiException
{
    public $httpCode = 403;
    public $errorLabel = 'portal_not_configured';
    public $messageLabel = 'EXCEPTION_PORTAL_NOT_CONFIGURED';
}
/**
 * URL does not resolve into a valid REST API method.
 */
class SugarApiExceptionNoMethod extends SugarApiException
{
    public $httpCode = 404;
    public $errorLabel = 'no_method';
    public $messageLabel = 'EXCEPTION_NO_METHOD';
}
/**
 * Resource specified by the URL does not exist.
 */
class SugarApiExceptionNotFound extends SugarApiException
{
    public $httpCode = 404;
    public $errorLabel = 'not_found';
    public $messageLabel = 'EXCEPTION_NOT_FOUND';
}
/**
 * Thrown when the client attempts to edit the data on the server that was already edited by
 * different client.
 */
class SugarApiExceptionEditConflict extends SugarApiException
{
    public $httpCode = 409;
    public $errorLabel = 'edit_conflict';
    public $messageLabel = 'EXCEPTION_EDIT_CONFLICT';
}

class SugarApiExceptionInvalidHash extends SugarApiException
{
    public $httpCode = 412;
    public $errorLabel = 'metadata_out_of_date';
    public $messageLabel = 'EXCEPTION_METADATA_OUT_OF_DATE';
}

class SugarApiExceptionRequestTooLarge extends SugarApiException
{
    public $httpCode = 413;
    public $errorLabel = 'request_too_large';
    public $messageLabel = 'EXCEPTION_REQUEST_TOO_LARGE';
}
/**
 * One of the required parameters for the request is missing.
 */
class SugarApiExceptionMissingParameter extends SugarApiException
{
    public $httpCode = 422;
    public $errorLabel = 'missing_parameter';
    public $messageLabel = 'EXCEPTION_MISSING_PARAMTER';
}
/**
 * One of the required parameters for the request is incorrect.
 */
class SugarApiExceptionInvalidParameter extends SugarApiException
{
    public $httpCode = 422;
    public $errorLabel = 'invalid_parameter';
    public $messageLabel = 'EXCEPTION_INVALID_PARAMETER';
}
/**
 * The API method is unable to process parameters due to some of them being wrong.
 */
class SugarApiExceptionRequestMethodFailure extends SugarApiException
{
    public $httpCode = 424;
    public $errorLabel = 'request_failure';
    public $messageLabel = 'EXCEPTION_REQUEST_FAILURE';
}

/**
 * The client is out of date for this version
 */
class SugarApiExceptionClientOutdated extends SugarApiException
{
    public $httpCode = 433;
    public $errorLabel = 'client_outdated';
    public $messageLabel = 'EXCEPTION_CLIENT_OUTDATED';
}

/**
 * We're in the maintenance mode
 */
class SugarApiExceptionMaintenance extends SugarApiException
{
    public $httpCode = 503;
    public $errorLabel = 'maintenance';
    public $messageLabel = 'EXCEPTION_MAINTENANCE';
}
