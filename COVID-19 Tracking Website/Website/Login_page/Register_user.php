<?php
session_start();
require_once "connect.php";
?>

<?php
	 if(isset($_POST['UserEmail']))
	 {
		 //FLAG
		 $succesfull=true;
		 
		 
		 
		 //TEST FIRST AND LAST NAME------------------------------------------------------------------
		 if ((isset($_SESSION['FirstName']))&&(isset($_SESSION['FirstName']))){
		 $UserName = $_POST[('FirstName'.' '.'LastName')];
		 
		 //length from 3 till 15 characters | strlen means str=string and len=lenght
		
			if((strlen($FirstName)<3)||(strlen($FirstName)>15))
			{
				$succesfull=false;
				$_SESSION['error_nick']=" First and last name must be at least 3 and up to 15 characters long";
			}
		
		
			if((strlen($LastName)<3)||(strlen($LastName)>15))
			{
				$succesfull=false;
				$_SESSION['error_nick']=" First and last name must be at least 3 and up to 15 characters long";
			}
		
		if (isset($_SESSION['$UserName'])){
			if(ctype_alnum($UserName)==false)
			{
				$succesfull=false;
				$_SESSION['error_nick']=" Your first and last name can include only letters! ";
			}
		}
	}
		 
		 //TEST AN EMAIL------------------------------------------------------------------------
		 
		 $UserEmail = $_POST['UserEmail'];
		 $safeEmail=filter_var($UserEmail, FILTER_SANITIZE_EMAIL); 
		
		 if((filter_var($safeEmail, FILTER_VALIDATE_EMAIL)==false) || ($safeEmail!=$UserEmail))
		 {
             if($UserEmail!=$ApplicantEmail){ //------------IF THERE IS NO THIS SPECIFIL EMAIL IN THE DB IT WILL NOT LET YOU TO REGISTER------
			 $succesfull=false;
			 $_SESSION['error_email']="Incorrect e-mail";}
		 }
		 
		 //TEST THE PASSWORD------------------------------------------------------------------------
		 if ((isset($_SESSION['password1']))&&(isset($_SESSION['password2']))){
		 $UserPassword1 = $_POST['password1'];
		 $UserPassword2 = $_POST['password2'];
		 
		 if((strlen($password1)<6)||(strlen($password1)>15))
		 {
			 $succesfull=false;
			 $_SESSION['error_password']=" The password must be at least 6 and up to 15 characters long";
		 }
		 
		 if($password1 != $password2)
		 {
			 $succesfull=false;
			 $_SESSION['error_password']=" Passwords must be identical!";
		 }
		 $password_hash = password_hash($password1, PASSWORD_DEFAULT);
        }
	}

        //TEST ACADEMIC INSTITUTION------------------------------------------------------------
			if (isset($_SESSION['AcademicInstitution'])){
            $AcademicInstitution = $_POST['AcademicInstitution'];
			if(ctype_alnum($AcademicInstitution)==false)
			{
				$succesfull=false;
				$_SESSION['error_AcademicInstitution']="Academic Institution can include only letters! ";
			}

		    }

        //TEST ACADEMIC ID------------------------------------------------------------------------
		if (isset($_SESSION['AcademicInstitutional_ID'])){
			$AcademicInstitutional_ID =$_POST['AcademicInstitutional_ID'];
             if(strlen($AcademicInstitutional_ID)!=8)
             {
                $succesfull=false;
                $_SESSION['error_AcademicInstitutional_ID']="The Academic ID has to be 8 digits";
             }
			}
			

		//USER CREATED ON----------------------------------------------------------------------------
		if(isset($_SESSION['UserCreatedOn'])){
	        $date=$_POST['UserCreatedOn'];
		    $date=GETDATE();
		}
		
		//USER PRIVILEGE-------------------------------------------------------------------------------
		if(isset($_SESSION['UserCreatedOn'])){
			 $UserPrivilege=$_POST['UserPrivilege'];
			 $UserPrivilege="0";
		}
			 

        //TEST IF IN THE DB ALREADY EXISTS CHOSEN EMAIL
        require_once "../includes/connect.php";
		try
		{
			$connect=@new mysqli($host, $database_user, $database_password, $database_name); 
			if($connect->connect_errno!=0)  
				{
				throw new Exception(mysqli_connect_errno());
				}
				else
				{	if(isset($_SESSION['UserEmail'])){
					//checking if the email already exists
					$result = $connect->query("SELECT UserID FROM users WHERE UserEmail='$UserEmail'");

					if(!$result) throw new Exception($connect->error);
				
					$numOfTheSameEmails = $result->num_rows;
					if($numOfTheSameEmails > 0)
					{
						$succesfull = false;
						$_SESSION['error_email']="This specific email already exists in our service and it is not available";
					}
				}
					if((isset($succesfull))&&(isset($_SESSION['UserName']))){
					if($succesfull==true)
					{
						//TESTS PASSED-------------------------------------------------------------------
						if($connect->query("INSERT INTO users VALUES(NULL,'$UserName','$UserPassword1','$UserEmail'", ))
						{
							$_SESSION['registered']=true;
							
						}
						
						else
						{
							throw new Exception($connect->error);
						}
						
						
					}
				}			
					$connect->close();
				}
				 
		}catch(Exception $exc)
		{
			echo "Server error. Please try again later.".$exc;
			
		}
		

		 ?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Login/Registration Page</title>
    <link rel="stylesheet" href="login_page.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
   

</head>

<body>
    <div id="container">
        <header>
            <div class="logo">
                <!--here will be the logo image-->

</header>

<main>
	<section>
    <div class="loginbox">
        <p><h1>---------------------Register---------------------</h1></p></br></br>
        <form method="post">

            <!----------------------------USERNAME--------------------------------->
            First name: <input type="text" name="FirstName" placeholder="First name..."/></br>
            Last name: <input type="text" name="LastName" placeholder="Last name...."/></br>
            <?php 
							if(isset($_SESSION['error_nick']))
							{
								echo '<div class="error">'.$_SESSION['error_nick'].'</div>';
								unset($_SESSION['error_nick']);
							}
						?>
			

            <!----------------------------EMAIL------------------------------------>
            E-mail: <input type="text" name="UserEmail" placeholder="Your e-mail..."/></br>
            <?php 
				if(isset($_SESSION['error_ApplicantEmail']))
				{
					echo '<div class="error">'.$_SESSION['error_ApplicantEmail'].'</div>';
					unset($_SESSION['error_ApplicantEmail']);
				}
			?>

            <!--------------------------PASSWORD--------------------------------------->
            Password: <input type="password" name="UserPassword1" placeholder="your password...."/></br>
            <?php 
				if(isset($_SESSION['error_password']))
				{
					echo '<div class="error">'.$_SESSION['error_password'].'</div>';
					unset($_SESSION['error_password']);
				}
			?>

            Re-enter Password: <input type="password" name="UserPassword2" placeholder=" re-enter your password...."/></br>


            <!--------------------------ACADEMIC INSTITUTION-------------------------------->

            Academic Institution: <input type="text" name="AcademicInstitution" placeholder="Academic Institution....."/></br>



            <!--------------------------ACADEMIC TITLE---------------------------------->
 
            Academic Title: </br>
            <div> 
                <select id="Academic Title" name="Academic Title" value="Academic Title">
                    <option value="Undergraduate">Undergraduate</option>
                    <option value="Postgraduate">Postgraduate</option>
                    <option value="Postdoc">Postdoc</option>
                    <option value="Lecturer">Lecturer</option>
                    <option value="Senior Lecturer/Reader">Senior Lecturer/Reader</option>
                    <option value="Professor">Professor</option>

                </select>
            </div>

            <!---------------------------ACADEMIC ID------------------------------------------>

          
            Academic ID: <input type="number" name="AcademicInstitutional_ID" placeholder="Academic ID...." />
            <?php 
				if(isset($_SESSION['error_AcademicInstitutional_ID']))
				{
					echo '<div class="error">'.$_SESSION['error_AcademicInstitutional_ID'].'</div>';
					unset($_SESSION['error_AcademicInstitutional_ID']);
				}
			?>


			<!---------------------------------SUBMIT--------------------------------------------->
            <input type="submit" value="Register" />
			<?php
				if(isset($_SESSION['registered']))
					{
					$_SESSION['registered']="Your register was successful!";
					echo '<div class="error">'.$_SESSION['registered'].'</div>';
					unset($_SESSION['registered']);
					exit();
					}
				else
					{
					unset($_SESSION['registered']);
					}
			?>




        </form>
    </div>
	</section>
</main>

</body>
</html>