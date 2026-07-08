<?php
	putenv('TZ = GMT');

	$connect= mysqli_connect("$DATABASE_SERVER", "$DATABASE_USERNAME","$DATABASE_PASSWORD","$DATABASE_NAME");
			if (!$connect)
			{
			printf("Can't connect to MySQL Server.", mysqli_connect_error());
			exit;
			}
?> 
