<?php
	// Don't Change anything else below [ unless you're running on a new server ... ]
	define('DBHOST', 'localhost');
	define('DBUSER', 'root');
	define('DBPASS', 'root');
	define('DBNAME', 'random');
	define('DBTABLE', 'existing_codes');
	define('ACCESS_PWD', '0fe0f3edb3161060a702b56e6317e3a1');
	
/***************************  MYSQL CONNECTION ***************************************/
	try {
		$db = mysql_connect(DBHOST,DBUSER,DBPASS);
		$sql = " CREATE DATABASE IF NOT EXISTS " .DBNAME;
		if (!mysql_query($sql)) { echo 'MySQL Error:' . mysql_error() .'<br />'; break; }
		if(mysql_select_db(DBNAME, $db)) {	return $db; }
		} catch (Exception $e) { echo 'Caught exception: ',  $e->getMessage(), "\n";
	} // END try block

/*************************** Salesforce Credentials *********************************/

define("USERNAME", "nelson.correia@groupon.co.uk");
define("PASSWORD", "password");
define("SECURITY_TOKEN", "sdfhkjwrhgfwrgergp");

?>