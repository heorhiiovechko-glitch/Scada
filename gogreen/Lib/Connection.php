<?php

$hostname="localhost";
$username="root";
$password="pvipl#2026!";

$dbname = "va_master";
//$conn=mysql_connect($hostname,$username,$password);
//$db=mysql_select_db($dbname);
/*$conn = new mysqli("$hostname", "$username","$password", "$dbname");
	if ($conn->connect_errno) {
		echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
	}*/
	$db=mysqli_connect("$hostname", "$username","$password", "$dbname");
	if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?>