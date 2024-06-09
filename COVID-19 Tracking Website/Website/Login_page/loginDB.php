<?php

session_start();

require_once "../includes/connect.php";
require_once "../includes/functions.php";

//@ - showing only number of error e.g. 2002 or 1045
$connect = @new mysqli($host, $database_user, $database_password, $database_name);




//checks if conecting to the database was successful | "1" means that there is an error | "0" means that the connection was succesfull
if ($connect->connect_errno != 0) {
	echo "Error: " . $connect->connect_errno;
} else {
	$UserEmail = $_POST['login'];
	$UserPassword = $_POST['password'];

	$UserEmail = htmlentities($UserEmail, ENT_QUOTES); // changing apostrofs and other signs for entities
	$UserPassword = htmlentities($UserPassword, ENT_QUOTES);



	//Sent result to the database by using method query(asking)
	if ($result = @$connect->query(
		sprintf(
			"SELECT * FROM users WHERE UserEmail='%s'",
			mysqli_real_escape_string($connect, $UserEmail)
		)
	)); {
		//checking how many users is with specific login and password by counting rows in the database
		$number_of_users = $result->num_rows;

		//someone succesfully loged in
		if ($number_of_users > 0) {

			session_unset();
			$_SESSION['loged'] = true;

			$verse = $result->fetch_assoc();

			$passwordHash = $verse['UserPassword'];

			if (!password_verify($UserPassword, $passwordHash)) {
				$_SESSION['error'] = '<span style="color:red">Login or Password is incorrect!</span>';
				logger("Unsucessful Login Attempted", $connect);
				$connect->close();
				header('location: ../Login_index.php');
				exit();
			}

			$_SESSION['userID'] = $verse['UserID'];
			$_SESSION['userType'] = $verse['UserPrivilege']; //verse = name of associate table and database name			
			$_SESSION['userEmail'] = $verse['UserEmail'];
			$_SESSION['userName'] = $verse['UserName'];

			$result->free_result();


			if ($_SESSION['userType'])  //Direct to the admin panel
			{

				$result = @$connect->query(
					sprintf(
						"SELECT * FROM admins WHERE AdminID='%s'",
						mysqli_real_escape_string($connect, $_SESSION['userID'])
					)
				);
				$verse = $result->fetch_assoc();
				$_SESSION['userPhone'] = $verse['AdminPhone'];
				$result->free_result();

				logger("Succesfully Logged In", $connect);

				header('Location: ../admin.php');
				unset($_SESSION['error']);
				
			} else {
				$result = @$connect->query(
					sprintf(
						"SELECT * FROM academics WHERE AcademicID='%s' AND AcademicIsApproved=1",
						mysqli_real_escape_string($connect, $_SESSION['userID'])
					)
				);

				$verse = $result->fetch_assoc();
				$_SESSION['userInstitution'] = $verse['AcademicInstitution'];
				$_SESSION['userAcademicTitle'] = $verse['AcademicTitle'];
				$_SESSION['userAcademicID'] = $verse['AcademicInstitutional_ID'];
				$result->free_result();

				logger("Succesfully Logged In", $connect);
				header('Location: ../account.php'); //direction to main php file
				unset($_SESSION['error']);
			}
		} else {
			$_SESSION['error'] = '<span style="color:red">Login or Password is incorrect!</span>';
			header('location: ../Login_index.php');
			logger("Unsucessful Login Attempted", $connect);
		}
	}
	$connect->close();
}
