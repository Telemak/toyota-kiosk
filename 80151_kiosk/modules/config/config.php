<?php

$debug = false;
/** 
* Database connection parameters
*/
if ($debug) {
	echo 'connection.php...<br />';
}

$hostname = "mysql.telemak.com";
$database = "toyota";
$username = "root";
$password = "t3l3m4K";
$pconnect = mysql_connect($hostname, $username, $password) or die('Could not connect: ' . mysql_error());; 

mysql_select_db($database, $pconnect);

/** 
* Format data for MySQL INSERT or UPDATE
*
* @param string $theValue
* @param string $theType
* @return String
*/
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}



?>