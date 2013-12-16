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

define("DEBUG", false);
define("CAPTURE_STDOUT", false);

$base_dir = dirname(__FILE__);

//----------------------------------------------------
$orig_path = get_include_path();
$new_path = "$base_dir/util" . PATH_SEPARATOR . "$base_dir/include" . PATH_SEPARATOR . "$base_dir/model" . PATH_SEPARATOR . $orig_path;
set_include_path($new_path);

$endpoints_map = array(
    "*:webmail"   => "SugarWebMailer",
    "*:mailqueue" => "SugarWebMailer",
);


if (CAPTURE_STDOUT) {
    ob_start();
}

//-----------------------------------------

define("BASE_PATH", "/cloud/sugarcrm");
define("SERVICE_API_CLASS", "SugarServiceApi");

require_once("commonsql.php");
require_once("utils.php");
require_once("SugarLogger.php");

require_once("api/SugarApiException.php");
require_once("api/SugarServiceApi.php");
require_once("api/SugarAuthenticationManager.php");

try {

    $db = getServiceDatabaseConnection();

    $REMOTE_ADDR    = $_SERVER["REMOTE_ADDR"];
    $QUERY_STRING   = $_SERVER["QUERY_STRING"];
    $REQUEST_URI    = $_SERVER["REQUEST_URI"];
    $HTTP_HOST      = $_SERVER["HTTP_HOST"];
    $REQUEST_METHOD = $_SERVER["REQUEST_METHOD"];

    if (empty($_SERVER["HTTP_API_USER"]) || empty($_SERVER["HTTP_API_PASS"])) {
        throw new SugarApiExceptionNotAuthorized('Missing Authorization Credentials');
    }

    $api_user = $_SERVER["HTTP_API_USER"];
    $api_pass = $_SERVER["HTTP_API_PASS"];
    $authManager = new SugarAuthenticationManager($db);
    if (!$authManager->authenticate($api_user, $api_pass)) {
        throw new SugarApiExceptionNotAuthorized('Invalid Authorization Credentials');
    }

    $tempArray = explode("?", $REQUEST_URI);
    $_uri_ = $tempArray[0];
    $_qs_ = '';
    $_get_args = array();
    if (count($tempArray) >= 2) {
        $_qs_ = $tempArray[1];
        if (!empty($_qs_)) {
            $_get_args = explode("&", $_qs_);
        }
    }

    if (strlen($_uri_) >= strlen(BASE_PATH) && substr($_uri_, 0, strlen(BASE_PATH)) == BASE_PATH) {
        $_uri_ = substr($_uri_, strlen(BASE_PATH));
    }
    if (substr($_uri_, 0, 1) != '/') {
        $_uri_ = '/' . $_uri_;
    }
    if (substr($_uri_, strlen($_uri_) - 1, 1) == '/') {
        $_uri_ = substr($_uri_, 0, strlen($_uri_) - 1);
    }

    $rest_params = explode("/", $_uri_);
    if (empty($rest_params[0])) {
        array_shift($rest_params);
    }

    $endpoint = '';
    if (count($rest_params) > 0) {
        $endpoint = $rest_params[0];
        // array_shift($rest_params);
    }

    $_post_args = array();
    if ($REQUEST_METHOD != "GET") {
        $input = file_get_contents('php://input', 1000000);
        if (!empty($input)) {
            $_post_args = json_decode($input, true);
        }
    }

    if (DEBUG) {
        printf("\n\n=============== REQUEST RECEIVED : %s ===============\n", $REQUEST_URI);
        printf("FILE-DIRECTORY    = %s\n", $base_dir);
        printf("REMOTE_ADDR       = %s\n", $REMOTE_ADDR);
        printf("QUERY_STRING      = %s\n", $QUERY_STRING);
        printf("REQUEST_URI       = %s\n", $REQUEST_URI);
        printf("SERVICE_URI       = %s\n", $_uri_);
        printf("HTTP_HOST         = %s\n", $HTTP_HOST);
        printf("REQUEST_METHOD    = %s\n", $REQUEST_METHOD);

        printf("endpoint          = %s\n", $endpoint);
        printf("rest_params       = \n");
        print_r($rest_params);
        printf("_get_args         = \n");
        print_r($_get_args);
        printf("_post_args        = \n");
        print_r($_post_args);
    }


    if (!file_exists('services')) {
        mkdir('services', 0777);
    }
    chdir("services");

    $map_key_method = $REQUEST_METHOD . ":" . $endpoint;
    $map_key_any = "*" . ":" . $endpoint;

    if (DEBUG) {
        printf("SERVICE:  %s\n", $endpoint);
        printf("METHOD:   %s\n", $REQUEST_METHOD);
        printf("MAP_KEY_METHOD:  %s\n", $map_key_method);
        printf("MAP_KEY_ANY:  %s\n", $map_key_any);
        print_r($endpoints_map);
    }

    $GLOBALS['log']->debug("REST Endpoint: $REQUEST_METHOD $endpoint");

    unset($map_key);
    if (isset($endpoints_map[$map_key_method])) {
        $map_key = $map_key_method;
    } elseif (isset($endpoints_map[$map_key_any])) {
        $map_key = $map_key_any;
    }

    if (!empty($endpoints_map[$map_key])) {
        $className = $endpoints_map[$map_key];

        $fileName = $className . ".php";
        if (file_exists($fileName)) {
            include($fileName);
        }

        if (class_exists($className)) {
            $reflectionClass = new ReflectionClass($className);

            if (!$reflectionClass->isSubclassOf(SERVICE_API_CLASS)) {
                throw new SugarApiExceptionNoMethod("API Class Has Invalid Parent: " . $className);
            }

            if (DEBUG) {
                printf("\n===========================================================================\n\n");
            }

            $obj = new $className();

            $apiSpecs = $obj->registerApiRest();

            $api = null;
            $args = array();
            foreach ($apiSpecs AS $key => $apidata) {
                if (strtoupper($apidata['reqType']) === $REQUEST_METHOD) {
                    $path = $apidata['path'];
                    $pathvars = $apidata['pathVars'];
                    $method = $apidata['method'];

                    if (count($path) != count($rest_params)) {
                        continue;
                    }
                    $match = true;
                    for ($i = 0; $i < count($path) && ($match); $i++) {
                        if (!($path[$i] === "?" || $path[$i] === $rest_params[$i])) {
                            $match = false;
                            break;
                        }
                    }
                    if (!$match) {
                        continue;
                    }

                    $api = $apidata;

                    if (DEBUG) {
                        print_r($apidata);
                        print_r($rest_params);

                        printf("COUNT-REST_PARAMS:   %d\n", count($rest_params));
                        printf("COUNT-PATH_ELEMENTS: %d\n", count($path));
                        printf("COUNT-PATH_VARS:     %d\n", count($pathvars));
                    }

                    for ($i = 0; $i < count($path); $i++) {
                        if ($path[$i] == "?" && count($pathvars) > $i) {
                            $args[$pathvars[$i]] = $rest_params[$i];
                        }
                    }
                    break;
                }
            }

            if (empty($api)) {
                throw new SugarApiExceptionNotFound("No Endpoint found for this URL");
            }

            $methodName = $api['method'];
            if (DEBUG) {
                printf("CLASS=%s  METHOD=%s\n", $className, $methodName);
            }

            if (!$reflectionClass->hasMethod($methodName)) {
                throw new SugarApiExceptionNoMethod("Method Not Found: " . $methodName . " in class " . $className);
            }

            $args = array_merge($args, $_get_args);
            $args = array_merge($args, $_post_args);

            if (DEBUG) {
                printf("-- ARGS --\n");
                print_r($args);
            }

            $obj->db = $db;
            $obj->customer_id = $api_user;

            $response = $obj->$methodName($args);

            if (CAPTURE_STDOUT) {
                $ob_status = ob_get_status();
                if (!empty($ob_status)) {
                    $output = ob_get_contents();
                    ob_end_clean();

                    if (!empty($output)) {
                        $statusCode = 500;
                        $message      = "extraneous data sent to stdout";
                        $errorLabel   = $e->getErrorLabel();

                        $result = array(
                            "code" => $statusCode,
                            "error"   => $errorLabel,
                            "error_message" => $message
                        );

                        header("HTTP/1.1 $statusCode");
                        echo json_encode($result) . "\n";
                        exit;
                    }
                }
            }

            echo json_encode($response) . "\n";

        } else {
            throw new SugarApiExceptionNotFound("Class File Not Found: " . $fileName);
        }
        return;
    } else {
        throw new SugarApiExceptionNotFound("Rest Endpoint Not Found: " . $map_key);
    }
} catch (SugarApiException $e) {
    if (CAPTURE_STDOUT) {
        ob_end_clean();
    }

    $statusCode   = $e->getHttpCode();
    $message      = $e->getMessage();
    $errorLabel   = $e->getErrorLabel();

    $result = array(
        "code" => $statusCode,
        "error"   => $errorLabel,
        "error_message" => $message
    );

    header("HTTP/1.1 $statusCode");

    echo json_encode($result) . "\n";
    return;
}

