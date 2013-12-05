<?php

function base64_encode_file($filename)
{
    $encoded = '';
    if ($filename && ($fh=fopen($filename, "r"))) {
        $bin = fread($fh, filesize($filename));
        $encoded = base64_encode($bin);
        fclose($fh);
    }
    return $encoded;
}

/**
 * determines if a passed string matches the criteria for a Sugar GUID
 * @param string $guid
 * @return bool False on failure
 */
function is_guid($guid)
{
    return strlen($guid) == 36 && preg_match("/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/i", $guid);

}

/**
 * A temporary method of generating GUIDs of the correct format for our DB.
 * @return String contianing a GUID in the format: aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
 *
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function create_guid()
{
    $microTime = microtime();
    list($a_dec, $a_sec) = explode(" ", $microTime);

    $dec_hex = dechex($a_dec* 1000000);
    $sec_hex = dechex($a_sec);

    ensure_length($dec_hex, 5);
    ensure_length($sec_hex, 6);

    $guid = "";
    $guid .= $dec_hex;
    $guid .= create_guid_section(3);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= $sec_hex;
    $guid .= create_guid_section(6);

    return $guid;

}

function create_guid_section($characters)
{
    $return = "";
    for ($i=0; $i<$characters; $i++) {
        $return .= dechex(mt_rand(0,15));
    }

    return $return;
}


function ensure_length(&$string, $length)
{
    $strlen = strlen($string);
    if ($strlen < $length) {
        $string = str_pad($string,$length,"0");
    } elseif ($strlen > $length) {
        $string = substr($string, 0, $length);
    }
}
