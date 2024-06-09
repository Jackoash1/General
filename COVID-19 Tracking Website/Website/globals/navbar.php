<!-- This is segmented for easier alteration should need come to pass later-->
<nav>

<!-- Only show before user has logged in (UserID unset) -->


<?php

$hideBtn=['','','',''];

if(!isset($_SESSION['userID']))
  $hideBtn=['',"style='display:none'","style='display:none'","style='display:none'"];

  
if(isset($_SESSION['userID']) && $_SESSION['userType'])
$hideBtn= ["style='display:none'", "", "", ""];

if(isset($_SESSION['userID']) && !($_SESSION['userType']))
$hideBtn= ["style='display:none'","","style='display:none'",""];
 
    echo " <div class='navbar_btn'".$hideBtn[0]."><box-icon type='solid' name='user'></box-icon></div>";
    echo " <div class='navbar_btn'><box-icon name='map-alt'></box-icon></div>";
    echo " <div class='navbar_btn'><box-icon name='line-chart'></box-icon></div>";
    echo " <div class='navbar_btn'".$hideBtn[1]."><box-icon name='user-account' type='solid' ></box-icon></div>";
    echo " <div class='navbar_btn'".$hideBtn[2]."><box-icon name='table' ></box-icon></box-icon></div>";
    echo " <div class='navbar_btn'".$hideBtn[3]."><box-icon name='log-out' type='solid' ></box-icon></div>";

?>


</nav>

<script>
  //add rediricting to other sites on buttons when needed
  let btns = document.getElementsByClassName("navbar_btn");
  
  btns[0].addEventListener("click", () => {window.location.href = "Login_index.php"})
  btns[1].addEventListener("click", () => {window.location.href = "index.php"})
  btns[3].addEventListener("click", () => {window.location.href = "account.php"})
  btns[4].addEventListener("click", () => {window.location.href = "admin.php"})
  btns[5].addEventListener("click", () => {window.location.href = "includes/session.kill.inc.php"})
</script>