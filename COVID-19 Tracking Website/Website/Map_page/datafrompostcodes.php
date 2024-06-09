<head>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;

            width: 100vw;
            height: 100vh;

            font-family: 'Nunito', sans-serif;
        }
        #main {
            width: 100vw;
            height: 100vh;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .info4User {
            font-size: 3rem;
            font-weight: 700;
        }
        .info4User:nth-child(2) {
            font-size: 1.7rem;
            font-weight: 400;
        }
    </style>
</head>
<body>
    <div id="main">
        <div class="info4User">Your request is being processed.</div>
        <div class="info4User">It can take up to a few seconds.</div>
    </div>
</body>

<?php

require_once("../includes/connect.php");
// ob_start();

$postcode = $_POST['postcode'];
$postcode = explode(" ", $postcode)[0];

echo "<div id='information' style='display:none;'>";

    //all other data
    $sql = "select * from postcodes where PostCode like '$postcode%' and PostCodePopulation > 0 and PostCodeTotalPositive > 1;";
    $result = mysqli_query($connect, $sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div class='info'>";
                echo "<div class='PostCodeID'>".$row['PostCodeID']."</div>";
                echo "<div class='PostCode'>".$row['PostCode']."</div>";
                echo "<div class='PostCodeLatitude'>".$row['PostCodeLatitude']."</div>";
                echo "<div class='PostCodeLangitude'>".$row['PostCodeLangitude']."</div>";
                echo "<div class='PostCodePopulation'>".$row['PostCodePopulation']."</div>";
                echo "<div class='PostCodeHouseholds'>".$row['PostCodeHouseholds']."</div>";
                echo "<div class='PostCodeRegion'>".$row['PostCodeRegion']."</div>";
                echo "<div class='PostCodeTotalPositive'>".$row['PostCodeTotalPositive']."</div>";
                echo "<div class='PostCodeTotalRecovered'>".$row['PostCodeTotalRecovered']."</div>";
                echo "<div class='PostCodeTotalDeceased'>".$row['PostCodeTotalDeceased']."</div>";
            echo "</div>";
        }
        //amount of cases in this post code
        $sql = "select count(PostCodeTotalPositive) as 'amount' from postcodes where PostCode like '$postcode%'";
        $result = mysqli_query($connect, $sql)->fetch_assoc();
        echo "<div class='info'>";
        echo $postcode;
        echo "</div>";
        echo "<div class='info'>";
        echo $result['amount'];
        echo "</div>";
    } else {
        echo "<div id='rej'></div>";
    }
echo '</div>';

echo "<script>";
    require_once("./dataToObject.js");
    // require_once("./pageRedirect.js");
echo "</script>";
?>