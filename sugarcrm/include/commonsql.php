<?php
//=========================================================================================================
// DATABASE Connections
//=========================================================================================================
function getServiceDatabaseConnection()
  {
    $dbhost     = "localhost";

    $dbname     = "sugar70";
    $dbuser     = "tjwolf";
    $dbpassword = "dragon";

    $db = mysql_pconnect($dbhost, $dbuser, $dbpassword);
    mysql_select_db($dbname,$db);
    return($db);
  }

