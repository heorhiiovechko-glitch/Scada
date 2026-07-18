<?php

$hostname = "localhost";
$username = "chennai_scada_app";
$password = "ChennaiSCADA_App_2026";
$dbname = "va_master";

$db = mysqli_connect($hostname, $username, $password, $dbname);
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_query($db, "SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");
$conn = $db;

if (!function_exists('mysql_query')) {
    function mysql_query($query, $link = null) {
        global $db;
        return mysqli_query($link ?: $db, $query);
    }
    function mysql_num_rows($result) {
        return $result ? mysqli_num_rows($result) : 0;
    }
    function mysql_fetch_array($result) {
        return $result ? mysqli_fetch_array($result) : null;
    }
    function mysql_fetch_assoc($result) {
        return $result ? mysqli_fetch_assoc($result) : null;
    }
    function mysql_real_escape_string($string, $link = null) {
        global $db;
        return mysqli_real_escape_string($link ?: $db, $string);
    }
    function mysql_insert_id($link = null) {
        global $db;
        return mysqli_insert_id($link ?: $db);
    }
    function mysql_error($link = null) {
        global $db;
        return mysqli_error($link ?: $db);
    }
}

?>
