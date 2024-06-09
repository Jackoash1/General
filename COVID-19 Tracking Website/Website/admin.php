<?php
include_once(dirname(__FILE__) . '../includes/connect.php');
include_once('head.php');


// ============ SESSION VARIABLES UTILZIED BY THIS PAGE =============

// 'adminPanelPage' - The current page of the admin panel.
// 'adminRowsPerPage'- The amount of rows displayed in the panel.
// 'adminTable' - The table displayed in the panel
// 'adminOrderBy' - The ordering column of the panel
// 'adminOrderType' - The ordering type (ASC or DESC)

//User is sent to index if they haven't logged in or are not an admin.
if (!isset($_SESSION['userID']) || (!$_SESSION['userType']))
    header('location: /index.php');

// Assume that if 1 of our session vars is set, they all are.
// If adminTable is unset, initiate session vars.
if (!isset($_SESSION['adminTable']))
    AdminPanel::setupAdminSession();

?>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Admin</title>

    <link rel='stylesheet' href='../styles/navbar.css'>
    <link rel='stylesheet' href='../styles/admin.css'>
    <!-- <script src='https://kit.fontawesome.com/cedd12acf6.js' crossorigin='anonymous'></script> -->
    <script src='https://unpkg.com/boxicons@2.1.2/dist/boxicons.js'></script>
</head>

<body>
    <div>

        <div class="std-box">
            <div class="forms">
                <!-- Create the table picking form. Selected Table is shown ontop. -->
                <div class="formContainer">
                    <form name="tablePicker" action='includes/admin.form.picker.inc.php' method='POST'>

                        <!-- Hidden input, used to tell which form has been used -->
                        <input type="hidden" name="formTarget" value="table" readonly>

                        <select name='value' onchange='tablePicker.submit();'>

                            <?php if ($_SESSION['userType'] == 2) {

                                $selected = '';

                                if ($_SESSION['adminTable'] == 'pending') $selected = 'selected';
                                echo "<option value='pending' " . $selected . " >Pending Approval </option>";

                                $selected = '';

                                if ($_SESSION['adminTable'] == 'admins') $selected = 'selected';
                                echo "<option value='admins' " . $selected . " >Admins </option>";
                            } ?>
                            <option <?php if ($_SESSION['adminTable'] == "academics") echo "selected" ?> value='academics'>Academics</option>
                            <option <?php if ($_SESSION['adminTable'] == "applicants") echo "selected" ?> value='applicants'>Applicants</option>
                            <option <?php if ($_SESSION['adminTable'] == "coviddata") echo "selected" ?> value='coviddata'>Covid Data</option>
                            <option <?php if ($_SESSION['adminTable'] == "postcodes") echo "selected" ?> value='postcodes'>Post-Codes</option>
                            <option <?php if ($_SESSION['adminTable'] == "logs") echo "selected" ?> value='logs'>Logs</option>
                        </select>
                    </form>
                </div>

                <!-- Create the Page Selection Form. Previously submitted page remains selected. -->
                <div class="formContainer">
                    <form name="pagePicker" action="includes/admin.form.picker.inc.php" method="POST">

                        <!-- Hidden input, used to tell which form has been used -->
                        <input type="hidden" name="formTarget" value="page" readonly>

                        <?php
                        $query = "SELECT FLOOR(COUNT(*)/" . $_SESSION["adminRowsPerPage"] . ") FROM " . $_SESSION['adminTable'];
                        $maxNum = AdminPanel::getQueryFromRange($query, 0, 1, $connect);

                        $lessThan = (int) $_SESSION['adminPanelPage'] - 1;
                        if ($lessThan < 0) $lessThan = 0;

                        $greaterThan = $_SESSION['adminPanelPage'] + 1;
                        if ($greaterThan > $maxNum[0][0]) $greaterThan = $maxNum[0][0];

                        //MaxNum[0][0] is a hacky way to get a single value without defining a separate function.

                        // The hidden button is there to avoid submit weirdness with LT and GT
                        echo "<input type='submit' value='default' id='hiddenBtn' readonly>";
                        echo "<button type='submit' name='lt' value='" . $lessThan . "'>&lt</button>";
                        echo "<input type='number' onkeypress='if(event.key==='Enter') pagePicker.submit();' min='0' max=" . $maxNum[0][0] . " name='value' value=" . $_SESSION['adminPanelPage'] . ">";
                        echo "<button type='submit' name='gt' value='" . $greaterThan . "'>&gt</button>";
                        ?>

                    </form>
                </div>

                <div class="formContainer">
                    <!-- Create the Row selection form. Previously selected number is shown ontop. -->
                    <form name="rowPicker" action="includes/admin.form.picker.inc.php" method="POST">

                        <!-- Hidden input, used to tell which form has been used -->
                        <input type="hidden" name="formTarget" value="row" readonly>

                        <select name='value' onchange="rowPicker.submit();">
                            <option <?php if ($_SESSION['adminRowsPerPage'] == 200) echo "selected" ?> value='200'>200</option>
                            <option <?php if ($_SESSION['adminRowsPerPage'] == 100) echo "selected" ?> value='100'>100</option>
                            <option <?php if ($_SESSION['adminRowsPerPage'] == 50) echo "selected" ?> value='50'>50</option>
                            <option <?php if ($_SESSION['adminRowsPerPage'] == 25) echo "selected" ?> value='25'>25</option>
                        </select>
                    </form>
                </div>
            </div>
            <?php
            // Set the two forms the Admin Table should use. 
            $phpForms = ['/includes/admin.table.header.inc.php'];

            // If the user is a standard admin or a super, use a different for form table updates.
            if ($_SESSION['userType'] == 2)
                array_push($phpForms, '/includes/admin.super.inc.php');
            else
                array_push($phpForms, '/includes/admin.standard.inc.php');




            // The class tags for our rendered table.
            // tags[0]: Table Classes
            // tags[1]: Table Row Classes
            // tags[2]: Table Cell Classes
            // tags[3]: Table Cell Input Field Classes
            $htmlTags = ['adminTable ', 'adminRow', 'adminCell', 'adminInput'];

            // Render Admin Table.
            AdminPanel::renderAdminFormTables($_SESSION['adminTable'], $phpForms, $connect, $_SESSION['adminOrderBy'], $htmlTags);
            ?>
        </div>
    </div>
    <?php include_once('globals/navbar.php'); ?>
</body>