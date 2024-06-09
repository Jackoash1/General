<?php
// ============================================ Log Function ==================================================

/**
 * Functions connects to database, grabs user IP, MAC address, and Email if session set.
 * It also displays a logging message.
 * 
 * @param string $log Message to be displayed in logs.
 * @param mysqli $connect The connection we execute querries over.
 * 
 * @param bool $return 1 if query succeeded, 0 if error occured.
 */
function logger($log, $connect)
{

  //Make file if it does not exist--------------------------------------------
  if (!file_exists('../log.txt'))  //Minimises chance of WGET data breach
    file_put_contents('../log.txt', ''); //Write data to the file

  //Grabbing Tracking Information---------------------------------------------
  $ip = $_SERVER['REMOTE_ADDR']; //Retrieve client ip address
  date_default_timezone_set('Etc/GMT0'); //Setting UK Timezone
  $date = date('Y-m-d'); //Date
  $time = date('H:i:s'); //Time

  if (isset($_SESSION['userID']))  //Checks User ID 
    $userID = $_SESSION['userID'];
  else
    $userID = "Null/Guest";


  if (isset($_SESSION['userEmail']))  //Checks UserEmail 
    $userEmail = $_SESSION['userEmail'];
  else
    $userEmail = "Null/Guest";


  if (isset($_SESSION['userType']))  //Checks User Type
    $userType = $_SESSION['userType'];
  else
    $userType = "Null";



  //Retrieving MAC Address----------------------------------------------------
  ob_start(); //Turn on output buffering
  system('ipconfig /all'); //Execute external program to display output
  $mycom = ob_get_contents(); //Capture the output into a variable
  ob_clean(); //Clean/erase the output buffer
  $findme = "Physical";
  $pmac = strpos($mycom, $findme); // Find the position of Physical text
  $mac = substr($mycom, ($pmac + 36), 17); // Get Physical Address


  //Reads file to a string----------------------------------------------------
  $contents = file_get_contents('../log.txt');
  //Arranging Tracking Information to our log file
  $contents .= "User ID: $userID\nMAC Address: $mac\nIP Address: $ip\nLog: $log\nDate: $date\nTime: $time\nEmail: $userEmail\r\n\n";
  //Appending the contents to the file
  file_put_contents('../log.txt', $contents);


  //Database Connection-------------------------------------------------------

  //Logging Information to Database-------------------------------------------
  $sql = "INSERT INTO logs (logIP, logMac, logEvent, logTime, logDate, logUserEmail, logUserType)
      VALUES ('$ip', '$mac', '$log', '$time', '$date', '$userEmail', '$userType')";
  return mysqli_query($connect, $sql);
}


// ============================================ ADMIN PANEL FUNCTIONALITY ==================================================


/**
 * This class is intended to make admin panel functionality easier to implement.
 * This clas is not meant to be instantiated, with all members being static.
 */
class AdminPanel
{

  /**
   * Executes MySQL а MySQL Querry. Offset and Range expected. Optionally Ordering could be specified for a given column.
   * 
   * @param string $query  The MySQL querry to be executed.
   * @param int $offset  Offset for a given range.
   * @param int $range  Number of rows (at max) to be returned.
   * @param mysqli $connect  The connection we utilize to execute statements over.
   * @param string $orderBy  The column we wish to order by (optional).
   * @param string $orderByType  The type of ordering (ASC or DESC). Will be replaced with '' if orderBy is ''.
   * @return array[] 2D Numbered array with received entries.
   */
  public static function getQueryFromRange($query, $offset, $range, $connect, $orderBy = '', $orderByType = '')
  {

    // If orderBy is empty, we want to make sure that we don't have ASC or DESC provided.
    if ($orderBy != '')
      $orderBy = " ORDER BY " . $orderBy;
    else
      $orderByType = '';

    $query = $query . $orderBy . " " . $orderByType . " LIMIT " . $offset . ", " . $range;
    $statement = mysqli_stmt_init($connect);
    if (!mysqli_stmt_prepare($statement, $query)) {
      // Echo the error that was given.
      echo mysqli_error($connect);
    }
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    return mysqli_fetch_all($result, $mode = MYSQLI_NUM);
  }

  /**
   * Initiates the admin page session vars to their default values if they haven't been set.
   * 
   * Default values and session names are:
   * Page: [0, adminPanelPage] 
   * Rows Per Page: [100, adminRowsPerPage]
   * Table: ['academics', adminTable]
   */
  public static function setupAdminSession()
  {
    $_SESSION['adminPanelPage'] = 0;
    $_SESSION['adminRowsPerPage'] = 100;
    $_SESSION['adminTable'] = 'academics';
    $_SESSION['adminOrderBy'] = '';
    $_SESSION['adminOrderType'] = 'ASC';
  }

  /**
   * Renders either a table or a form table based on provided tableName. Essentially a wrapper to save up space in the admin page. 
   * 
   * @param string $tableName  The name of the table we wish to bring up
   * @param string[] $formAction  Link to PHP script we wish to execute in forms. [0] for header, [1] for rows.
   * @param mysqli $connect  Database connection
   * @param string $orderBy The column we wish to order by
   * @param string[] $htmlTags The HTML tags to be provided for the rendered table
   */
  public static function renderAdminFormTables($tableName, $formAction, $connect, $orderBy, $htmlTags = ['', '', '', ''])
  {

    $headerList = [];
    $orderByList = [];
    $data = [];
    $query = '';

    // Check if table provided is within the approved list.
    switch ($tableName) {
      case "admins":
        $query = "SELECT u.UserID, u.UserName, u.UserEmail,a.AdminPhone, u.UserPrivilege ,u.UserCreatedOn FROM users AS u, admins AS a WHERE u.UserID= a.AdminID";
        $orderByList = ["UserID", "UserName", "UserEmail", "AdminPhone", "UserPrivilege", "UserCreatedOn"];
        $headerList = ["ID", "Name", "Email", "Phone", "Privilege", "Created On"];

        $data = AdminPanel::getQueryFromRange($query, $_SESSION['adminPanelPage'] * $_SESSION['adminRowsPerPage'],  $_SESSION['adminRowsPerPage'], $connect, $orderBy, $_SESSION['adminOrderType']);
        AdminPanel::renderFormTable($tableName, $headerList, $orderByList, [0], ['Update', 'Delete'], $data, $formAction, $htmlTags);

        return;

      case "academics":
        $query = "SELECT u.UserID, u.UserEmail, u.UserName, a.AcademicTitle, a.AcademicInstitution, a.AcademicInstitutional_ID, a.AcademicIsApproved, u.UserCreatedOn FROM users AS u, academics AS a WHERE u.UserPrivilege=0 AND u.UserID= a.AcademicID";
        $orderByList = ["UserID", "UserEmail", "UserName", "AcademicTitle", "AcademicInstitution", "AcademicInstitutional_ID", "AcademicIsApproved", "UserCreatedOn"];
        $headerList = ["ID", "Email", "Name", "Title", "Institution", "ID in Institution", "Approved?", "Created On"];

        $data = AdminPanel::getQueryFromRange($query, $_SESSION['adminPanelPage'] * $_SESSION['adminRowsPerPage'],  $_SESSION['adminRowsPerPage'], $connect, $orderBy, $_SESSION['adminOrderType']);
        AdminPanel::renderFormTable($tableName, $headerList, $orderByList, [0], ['Update', 'Delete'], $data, $formAction, $htmlTags);

        return;

      case "coviddata":
        $query = "SELECT c.DataID, p.PostCodeID ,p.PostCode, c.DataDate, c.DataPositive, c.DataHospitalized, c.DataIntensiveCare, c.DataDeceased  FROM postcodes AS p, coviddata AS c WHERE p.PostCodeID= c.DataPostCodeID";
        $orderByList = ["DataID", "PostCodeID", "PostCode", "DataDate", "DataPositive", "DataHospitalized", "DataIntensiveCare", "DataDeceased"];
        $headerList = ["Data ID", "PostCodeID", "Post Code", "Date", "Positive Cases", "Hospitalized Cases", "Intensive Care Cases", "Deceased"];

        $data = AdminPanel::getQueryFromRange($query, $_SESSION['adminPanelPage'] * $_SESSION['adminRowsPerPage'],  $_SESSION['adminRowsPerPage'], $connect, $orderBy, $_SESSION['adminOrderType']);
        AdminPanel::renderFormTable($tableName, $headerList, $orderByList, [0, 1, 2], ['Update', 'Delete'], $data, $formAction, $htmlTags);

        return;

      case "postcodes":
        $query = "SELECT * FROM " . $tableName;
        $orderByList = ["PostCodeID", "PostCode", "PostCodeLatitude", "PostCodeLongitude", "PostCodePopulation", "PostCodeHouseholds", "PostCodeRegion", "PostCodeTotalPositive", "PostCodeTotalRecovered", "PostCodeTotalDeceased"];
        $headerList = ["Code ID", "Post Code", " Latitude", "Longitude", "Population", "Household Number", "Region", "Total Positive", "Total Recovered", "Total Deceased"];

        $data = AdminPanel::getQueryFromRange($query, $_SESSION['adminPanelPage'] * $_SESSION['adminRowsPerPage'],  $_SESSION['adminRowsPerPage'], $connect, $orderBy, $_SESSION['adminOrderType']);
        AdminPanel::renderFormTable($tableName, $headerList, $orderByList, [0], ['Update', 'Delete'], $data, $formAction, $htmlTags);

        return;


      case "pending":
        $query = "SELECT p.PendingID, a.AdminID, u.UserEmail, p.PendingTarget, p.PendingDataAction,p.PendingDataTypes,p.PendingDataValues, p.PendingDataDate FROM pending as p, admins AS a, users as u WHERE u.UserID=a.AdminID AND p.PendingAdminID=a.AdminID";
        $orderByList = ["PendingID", "AdminID", "UserEmail", "PendingTarget", "PendingDataTypes", "PendingDataAction", "PendingDataValues", "PendingDataDate"];
        $headerList = ["Request ID", "Admin ID", "Admin Email", "Target Table", "Action", "Data Types", "Values", "Request Date"];

        $data =  $data = AdminPanel::getQueryFromRange($query, $_SESSION['adminPanelPage'] * $_SESSION['adminRowsPerPage'],  $_SESSION['adminRowsPerPage'], $connect, $orderBy, $_SESSION['adminOrderType']);
        AdminPanel::renderFormTable($tableName, $headerList, $orderByList, [0, 1, 2, 3, 4, 5, 6], ['Approve', 'Delete'], $data, $formAction, $htmlTags);

        return;

      case "applicants":
        $query = "SELECT * FROM " . $tableName . " WHERE " . $tableName . ".ApplicantApproved !=1 ";
        $orderByList = ["ApplicantID", "ApplicantEmail", "ApplicantApplication", "ApplicantApproved"];
        $headerList = ["Applicant ID", "Email", "Comment", "Approved?"];
        $formAction[1] = 'includes/application.inc.php';

        $data =  $data = AdminPanel::getQueryFromRange($query, $_SESSION['adminPanelPage'] * $_SESSION['adminRowsPerPage'],  $_SESSION['adminRowsPerPage'], $connect, $orderBy, $_SESSION['adminOrderType']);
        AdminPanel::renderFormTable($tableName, $headerList, $orderByList, [0, 1, 2, 3], ['Approve', 'Delete'], $data, $formAction, $htmlTags);

        return;

      case "logs":
        //As logs are not meant to be edited, render a standard table.
        $query = "SELECT * FROM " . $tableName;
        $orderByList = ["logID", "logIP", "logMac", "logEvent", "logTime", "logDate", "logUserEmail", "logUserType"];
        $headerList = ["Log ID", "IP Address", "MAC Address", "Event", "Time", "Date", "User Email", "User Type"];

        $data = AdminPanel::getQueryFromRange($query, $_SESSION['adminPanelPage'] * $_SESSION['adminRowsPerPage'],  $_SESSION['adminRowsPerPage'], $connect, $orderBy, $_SESSION['adminOrderType']);
        AdminPanel::renderTable($headerList, $orderByList, $data, $htmlTags, $formAction[0]);

        return;

      default:
        echo "Non-existent table (" . $tableName . " ) provided";
        return;
    }
  }

  /**
   * Renders a Table in which the headers are a form, and every table row is a form. Optionally, buttons can be rendered on the righ-hand side.
   * 
   * @param string $tableName The name of the table we want to render (Used for table row forms).
   * @param string[] $headerList  Contents of the table headers.
   * @param string[] $orderByList MySQL names of table columns. Used for sorting
   * @param int[] $readOnlyList Integer List of Column IDs to be set as read-only
   * @param string[] $buttonList String List of values for the submission buttons.
   * @param array[] $data 2D array of table contents.
   * @param string[] $formAction Forms to be executed. $formAction[0] is Header, $formAction[1] is table rows.
   * @param string[] $htmlTags [0]: table tags, [1]: row tags, [2]: cell tags, [3]: Input tags.
   */
  public static function renderFormTable($tableName, $headerList, $orderByList, $readOnlyList, $buttonList, $data, $formAction = ['#', '#'], $htmlTags = ['', '', '', ''])
  {

    // ========================= CONDITIONAL CHECKS ============================

    // Make sure our data isnt empty.
    if (empty($data)) {
      echo "<br><t>No entries at this page<t><br>";
      return;
    }

    // Print error if mismatch in amount of columns and provided names
    if (count($headerList) < count($data[0])) {
      echo "<br><t>Table Rendering Error: More Columns than Column Headers</t><br>";
      return;
    }

    // Print error if mismatch in number of headers and header values.
    if (count($headerList) != count($orderByList)) {
      echo "<br><t>Table Rendering Error: Headers and OrderBy List Mismatch.</t><br>";
      echo count($headerList) . " " . count($orderByList);
      return;
    }

    // Make sure our arrays are sufficiently sized.
    while ($htmlTags < 2)
      array_push($formAction, '#');

    while ($htmlTags < 4)
      array_push($htmlTags, '');

    // Provided we have a read only-list, sort it numerically.
    if (count($readOnlyList))
      sort($readOnlyList, SORT_NUMERIC);


    // ============================ SETUP HEADERS ==============================

    // Echo Table
    echo "<table class='" . $htmlTags[0] . "'>";

    // Echo Table Header (With Sorting.)
    echo "<tr><form action='" . $formAction[0] . "' method='POST'>";
    for ($i = 0; $i < count($headerList); $i++)
      echo "<th class='" . $htmlTags[1] . "'><button class='head-btn' name='header' type='submit' value='" . $orderByList[$i] . "'>" . $headerList[$i] . "</button></th>";

    // Echo Table Header (Without Sorting)
    for ($i = 0; $i < count($buttonList); $i++)
      echo "<th class='" . $htmlTags[1] . "'> " . $buttonList[$i] . " </th>";
    echo "</form></tr>";


    // ============================= SETUP CONTENTS ============================

    // Echo Rows
    for ($i = 0; $i < count($data); $i++) {
      echo "<tr class='" . $htmlTags[1] . "'><form action='" . $formAction[1] . "' method='POST'>";

      // Echo Row Data
      for ($j = 0; $j < count($data[0]); $j++) {

        $readOnlyStart = 0;
        $readOnlyString = '';

        //If element of index J belongs to the read-only array, give it the readonly property. 
        for ($k = $readOnlyStart; $k < count($readOnlyList); $k++) {

          if ($j == $readOnlyList[$k]) {
            $readOnlyStart++;
            $readOnlyString = 'readonly';
            break 1;  //Escape this loop if there's a match.
          } else
            $readOnlyString = '';
        }

        // Echo Row Cells.
        echo "<td class='" . $htmlTags[2] . "'><input class='" . $htmlTags[3] . "' type='text' size='" . strlen($data[$i][$j]) . "' name='input[" . $j . "]' value='" . $data[$i][$j] . "' " . $readOnlyString . " required > </td>";
      }

      // Echo Submission Buttons
      for ($j = 0; $j < count($buttonList); $j++)
        echo "<td class=' " . $htmlTags[2] . "'> <button class='side-btn ' name='submit' type='submit' value='" . $buttonList[$j] . "'>" . $buttonList[$j] . "</button></td>";

      // We want to know the table name
      echo "<input type='hidden' name='table' value='" . $tableName . "' readonly>";
      echo "</form></tr>";
    }
    echo "</table>";
  }

  /**
   * Echo's an HTML table, taking in a list of strings for table headers.
   * 
   * @param string[] $headerList  The names of all columns
   * @param array[] $data  2D array of data to be displayed in table rows
   * @param string $tableTags  String of HTML tags to give to table
   * @param string $rowTags  String of HTML tags to give to rows
   * @param string $cellTags  String of HTML tags to give to cells
   */
  public static function renderTable($headerList, $orderByList, $data, $htmlTags, $formAction)
  {

    // Make sure our data isnt empty.
    if (empty($data)) {
      echo "<br><t>No entries at this page<t><br>";
      return;
    }

    // Print error if mismatch in amount of columns and provided names
    if (count($headerList) < count($data[0])) {
      echo "<br><t>Table Rendering Error: More Columns than Column Headers</t><br>";
      return;
    }

    // Print error if mismatch in number of headers and header values.
    if (count($headerList) != count($orderByList)) {
      echo "<br><t>Table Rendering Error: Headers and OrderBy List Mismatch.</t><br>";
      echo count($headerList) . " " . count($orderByList);
      return;
    }

    echo "<table class='" . $htmlTags[0] . "'>";


    // Setup table headers.
    echo "<table class='" . $htmlTags[0] . "'>";
    echo "<tr><form action='" . $formAction . "' method='POST'>"; // Setup the headers

    for ($i = 0; $i < count($headerList); $i++)
      echo "<th class='" . $htmlTags[1] . "'><button name='header' class='head-btn' type='submit' value='" . $orderByList[$i] . "'>" . $headerList[$i] . "</button></th>";

    echo "</form></tr>";

    for ($i = 0; $i < count($data); $i++) {
      echo "<tr class='" . $htmlTags[1] . "'>";

      for ($j = 0; $j < count($data[0]); $j++)
        echo "<td class='" . $htmlTags[2] . "'>" . $data[$i][$j] . "</td>";

      echo "</tr>";
    }
    echo "</table>";
  }
};
