<?php include_once 'header.php' ?>
<?php include_once 'includes/dbh.inc.php' ?>

<div class="wrapper">
    <div class="store-box">

        <h2 class="text-center alignt-self-center">Inventory</h2>

        <!-- This bit is the file upload form. It's atop the page for easier access if there are theoretically hundreds of items -->
        <?php
        // Only show this if the user is an admin.
        if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] !== 0) {
            echo '
            <div class="base-box container-sm form-box shadow-std mt-3" id="loginContainer">

            <h1>Add Item</h1>

            <form action="includes/upload.inc.php" method="POST" enctype="multipart/form-data">

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
                            <input type="text" name="itemName" placeholder="Product Brand or Name" id="itemName" class="form-control" required>
                        </div>
                    </div>

                    <!-- Item Price -->
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="itemPrice">Item Price:</label>
                            <input type="number" step="any" min="0" name="itemPrice" placeholder="19.99" id="itemPrice" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="row pt-2">

                    <!-- Item Quantity -->
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="itemQuantity">Item Quantity:</label>
                            <input type="number" name="itemQuantity" placeholder="1234 etc." id="itemQuantity" class="form-control" required>
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
                    <button class="btn btn-dark shadow-std" name="submit" type="submit">Commit</button>
                </div>
            </form>
        </div> 
        ';
        } ?>


        <!-- This bit of code iterates all of the ID's in the database and displays them as divs -->
        <div class="row text-center align-self-center">
            <?php
            // Grab products by descending order
            $sql = "SELECT * FROM products ORDER BY productsId DESC";
            $statement = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($statement, $sql)) {
                echo "Statement failure";
            }
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);

            while ($row = mysqli_fetch_assoc($result)) {
                echo '  
                    <div class="p-2 pt-3 col-sm-6 col-xs-12 col-md-3 col-lg-2 align-self-center ">
                    <div class="item-box">
                    <a href="product.php?id=' . $row["productsId"] . '">
                        <div style="background-image: url(img/' . $row["productsImageFilepath"] . ')"></div>
                        <h5>' . $row["productsName"] . '</h5>
                        <p class="pl-4 text-left">' . "Price: <span>£" . $row["productsPrice"] . '</span></p>
                     </a>
                    </div>
                    </div>
                    ';
            } ?>
        </div>
    </div>
</div>
<?php include_once 'footer.php' ?>