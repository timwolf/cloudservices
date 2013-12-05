<?php
//=========================================================================================================
// DATABASE Connections
//=========================================================================================================
function getServiceDatabaseConnection()
  {
    $dbhost     = "localhost";

    $dbname     = "cloudservices";
    $dbuser     = "ponyexpress";
    $dbpassword = "campaigns";

    $db = mysql_pconnect($dbhost, $dbuser, $dbpassword);
    mysql_select_db($dbname,$db);
    return($db);
  }

