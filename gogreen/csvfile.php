<?php
error_reporting(-1);
error_reporting(E_ALL); 
ini_set('display_errors', 1);
// output headers so that the file is downloaded rather than displayed
//header('Content-Type: text/csv; charset=utf-8');
//header('Content-Disposition: attachment; filename=customer_data.csv');

// create a file pointer connected to the output stream
$output = fopen('C:\data\datafile.csv', 'w');

// output the column headings
fputcsv($output, array('#IMEI','Device_Name', 'Format_Type', 'Pocket_Length','Db_Name'));

// fetch the data
mysql_connect('localhost', 'vscada', 'vscada600038');
mysql_select_db('va_master');
$rows = mysql_query('SELECT IMEI,Device_Name,Format_Type,Pocket_Length,Db_Name FROM device_register');

// loop over the rows, outputting them
while ($row = mysql_fetch_assoc($rows)) { 
//fputcsv($output, $row);

file_put_contents('datafile.csv', $row);
}
?>