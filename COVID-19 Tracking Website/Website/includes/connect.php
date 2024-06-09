<?php
	//This is Natalia's code, I simply altered comments slightly

	//Attempt to connect with MySQL database.
	$host="localhost";
	$database_user="id18665461_enterpriseproteam08";
	$database_password=">e$3XaWF%~@h~nf7";
	$database_name="id18665461_enterpriseproteam08db";
	
	//for testing (change before putting on the hosting)
	$host="localhost";
	$database_user="root";
	$database_password="";
	$database_name="covid-dashboard";
	//end of testing code
	
	$connect = @new mysqli($host, $database_user, $database_password, $database_name); 
	
	//Send error if connection failure occurs, as well as error msg.
	if($connect->connect_error){
		die("Connection failed: ".$connect->connect_error);
	}
?>