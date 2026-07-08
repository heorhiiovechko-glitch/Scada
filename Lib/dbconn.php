<?php	
	putenv('TZ = GMT');
	$DATABASE_TYPE="MYSQL";
	$DATABASE_SERVER="localhost";
	$DATABASE_PORT="3306";
	$MASTER_DATABASE_NAME="va_master";
	$DATABASE_USERNAME="root";
	$DATABASE_PASSWORD="pvipl#2026!";	
	/*$db = mysql_connect("$DATABASE_SERVER:$DATABASE_PORT", "$DATABASE_USERNAME","$DATABASE_PASSWORD");
	//$db_victory = mysql_connect("$DATABASE_SERVER:$DATABASE_PORT", "$DATABASE_USERNAME","$DATABASE_PASSWORD",TRUE);
	mysql_select_db("$MASTER_DATABASE_NAME");*/
	//mysql_select_db("$VICTORY_DATABASE_NAME");
	$db = new mysqli("$DATABASE_SERVER:$DATABASE_PORT", "$DATABASE_USERNAME","$DATABASE_PASSWORD", "$MASTER_DATABASE_NAME");
	if ($db->connect_errno) {
		echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	}
	
?>