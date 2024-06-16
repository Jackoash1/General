<html>
<head>
<title>Login Form</title>
<script language="JavaScript" type="text/JavaScript">

</script>
</head>
<body>
<h1 align="center">Search for Users</h1>
    <br />
    <!-- Search form -->
    <div align="center">
    <form method="post"  action="database.php" onsubmit="return validate(this);">
        <fieldset style="width:550px" align="center">
        <legend align="left"><b>Search for Users</b></legend>
               <div>
<br/>
                <label>Search:</label>
                <input type="text" name="database" placeholder="Type Here"  size="24" />&nbsp;<select name="itemType">
                                                                  <option value="" name="item">    </option>
                                                                  <option value="username">Username</option>
                                                                  <option value="productName">Product Name</option>
																  <option value="productDescription">Product Description</option>
                                                                  </select> &nbsp;&nbsp;<input value="Search" type="submit" name="Search"/> <br/><span id="searchmsg"></span>
            </div>

           
       </fieldset>
       
    </form>

<?php
include("../db/DB_connect1.php"); 
if (isset($_POST['Search'])) {
  // receive all input values from the form
  
 $prodItem =  $_POST['database'];
 $type = $_POST['itemType'];
  
   if ($type=="username"){
      $sql="SELECT * FROM users WHERE Username LIKE '%$prodItem%'";
    }elseif ($type=="productName"){
      $sql="SELECT * FROM product WHERE product_name LIKE '%$prodItem%'";
    }elseif ($type=="productDescription"){
		$sql="SELECT * FROM product WHERE product_description LIKE '%$prodItem%'";
	}
 echo "<br/>";
 
$result = mysqli_query($db, $sql);
 $number = $result->num_rows;
  
echo "<h2 align='center'>Search Results  </h2>";
 if ($number==0){
    echo "<h3 align='center' style='background-color:red; width:250px'> No Results  </h3>";
 }else{

//$row = mysqli_fetch_assoc($result);

echo "<table border='1'style='width:650px' >";
// There is the table header 
echo" <tr style='background-color:powderblue;'>";
    echo "<th>Username</th>";
    echo "<th>Product Name</th>";
    echo "<th>Product Description</th>";
   echo "<th>Product Quantity</th>";
   echo "<th>Product Price</th>";
   echo "<th>Edit</th>";
   echo "<th>Delete</th>";
  

echo" </tr>";
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
// Here display all the recodes of SQL results
echo "<tr align='center'>";
  echo "<td>".$row['Username']. "</td>";
  echo "<td>".$row['product_name']. "</td>";
echo "<td>".$row['product_description']. "</td>";
echo "<td>".$row['product_quantity']. "</td>";
  echo "<td>".$row['product_price']. "</td>";
  echo "<td align='center'><a href='#' onClick=window.open('productEdit.php?productid=".$row['Username']. "');>Edit</a></div></td>";
echo "<td align='center'><a href='#' onClick=window.open('productEdit.php?productid=".$row['Username']. "');>Remove</a></div></td>";
 
 echo "</tr>";

}
echo "</table>";

}
}
?>
</div>

</body>
</html>