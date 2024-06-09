<?php

include_once 'includes/dbh.inc.php';
include_once 'header.php';

// Get product ID from URL and run SQL query to get the rest of its data.

$pageID = $_GET['id'];
$sql = "SELECT * FROM products WHERE productsId =" . $pageID;
$statement = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($statement, $sql)) {
    echo "<p>Statement failure</p>";
}
mysqli_stmt_execute($statement);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($statement));

echo '
<div class="wrapper">
<div class="product-box shadow-std">

    <div class="row">
        <h2 class="col-12 shadow-std">' . $row['productsName'] . '</h2>
    </div>

    <div class="row p-4">

        <div class="col-sm-12 col-md-12 col-lg-6 shadow-std mb-4" id="imgHolder" style="background-image: url(img/' . $row['productsImageFilepath'] . '"></div>

        <div class="col-sm-12 col-md-12 col-lg-6 p-2">

            <div class="container mb-4">
                <div class="row">
                
                    <div class="col-md-12 col-lg-6 col-sm-12">
                        <h4 class="shadow-std">Price: <span style="color: var(--iceBlue)">£' . $row['productsPrice'] . '</span></h4>
                    </div>

                    <div class="col-md-12 col-lg-6 col-sm-12">
                        <h4 class="shadow-std">In Stock:<span style="color: var(--red)"> ' . $row['productsQuantity'] . '</span></h4>
                    </div>

                </div>
            </div>

            <div class="p-3">
                <div class="container p-4 shadow-std" id="desc-box">
                    <p>' . $row['productsDescription'] . '</p>
                </div>
            </div>

        </div>

    </div>
</div>
</div>
    ';
?>


<?php
// Only show this if the user is an admin.
if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] !== 0) {
    echo '
    <div class="base-box container-sm form-box shadow-std mt-3" id="loginContainer">

    <h1>Update Item</h1>

    <form action="includes/update-product.inc.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="itemId" value="' . $row['productsId'] . '">

        <!-- Image Upload Field -->
        <div class="align-self-center text-center br-6 p-4 m-2 bg-lighter shadow-std">
            <div class="form-group">
                <label for="itemImage" class="col-12">Item Image:</label>
                <input type="file" name="itemImage" id="itemImage">
            </div>
        </div>

        <div class="row pt-2">

            <!-- Item Name -->
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="itemName">Item Name:</label>
                    <input type="text" name="itemName" placeholder="Product Brand or Name" id="itemName" class="form-control">
                </div>
            </div>

            <!-- Item Price -->
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="itemPrice">Item Price:</label>
                    <input type="number" step="any" name="itemPrice" placeholder="19.99" id="itemPrice" class="form-control">
                </div>
            </div>
        </div>

        <div class="row pt-2">

            <!-- Item Quantity -->
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="itemQuantity">Item Quantity:</label>
                    <input type="number" name="itemQuantity" placeholder="1234 etc." id="itemQuantity" class="form-control">
                </div>
            </div>

            <!-- Item Descriptor -->
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="itemDescription">Brief Item Description:</label>
                    <input type="text" name="itemDescription" placeholder="Clothing, Size M, etc." id="itemDescription" class="form-control">
                </div>
            </div>

        </div>

        <!-- Submit Button -->
        <div class="align-self-center text-center pt-4">
            <button class="btn btn-dark shadow-std" name="submit" type="submit">Update</button>
        </div>
    </form>
</div>

        ';
} ?>








<?php include_once 'footer.php' ?>