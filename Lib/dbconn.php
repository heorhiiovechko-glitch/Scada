<?php	
	putenv('TZ = GMT');
	$DATABASE_TYPE="MYSQL";
	$DATABASE_SERVER="localhost";
	$DATABASE_PORT="3306";
	$MASTER_DATABASE_NAME="va_master";
	$DATABASE_USERNAME="chennai_scada_app";
	$DATABASE_PASSWORD="ChennaiSCADA_App_2026";	
	/*$db = mysql_connect("$DATABASE_SERVER:$DATABASE_PORT", "$DATABASE_USERNAME","$DATABASE_PASSWORD");
	//$db_victory = mysql_connect("$DATABASE_SERVER:$DATABASE_PORT", "$DATABASE_USERNAME","$DATABASE_PASSWORD",TRUE);
	mysql_select_db("$MASTER_DATABASE_NAME");*/
	//mysql_select_db("$VICTORY_DATABASE_NAME");
	$db = new mysqli("$DATABASE_SERVER:$DATABASE_PORT", "$DATABASE_USERNAME","$DATABASE_PASSWORD", "$MASTER_DATABASE_NAME");
	if ($db->connect_errno) {
		echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	}
	$db->query("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");
	
?>