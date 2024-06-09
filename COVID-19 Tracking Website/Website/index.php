<!-- Include the head part of the doc-->
<?php include_once('head.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="./styles/map_page.css">
    <link rel="stylesheet" href="./styles/navbar.css">
    <!-- <script src="https://kit.fontawesome.com/cedd12acf6.js" crossorigin="anonymous"></script> -->
    <script src="https://unpkg.com/boxicons@2.1.2/dist/boxicons.js"></script>
</head>

<body>
    <div id="searchModal">
        <?php include_once("./Map_page/form.php"); ?>
        <div id="darkener"></div>
    </div>
    <iframe id="google-map-embed" width="450" height="250" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA-GdBSgDjdpLSvczF4JcBrdD967SA-wEQ&q=BD7" allowfullscreen>
    </iframe>

    <!-- <div id="content"></div> -->
    <div id="graphsMenu">
        <div class="graphDiv"><canvas class="graphCanvas"></canvas></div>
        <div id="chartChangeBtns">
            <!-- <div class="chooseChartTypeBtn">Bar</div> -->
            <!-- <div class="chooseChartTypeBtn">Line</div> -->
            <!-- <div class="chooseChartTypeBtn">Circle</div> -->
        </div>
    </div>

    <!-- Include the navigation buttons-->
    <?php include_once('./globals/navbar.php') ?>

    <script src="./Map_page/randomColor.js"></script>
    <script src="./Map_page/dataFromLS.js"></script>
    <script src="./Map_page/js.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/1.2.1/chartjs-plugin-zoom.min.js"></script>
    <script src="./Map_page/charts.js"></script>

</body>

</html>