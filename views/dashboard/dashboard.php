<?php
    require './controllers/php/checkLoginController.php';
    $cookieJWT = new Jwt ($_COOKIE['JWT']);
    $userVerifiedData = $cookieJWT->getDataFromJWT($cookieJWT->token);
    if(!$cookieJWT->verifyJWT($cookieJWT->token)){
        //If the user is not an authenticated user then they will be redirected to the home page
        header('Location: http://localhost:8081/home');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connects Dashboard</title>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="/css/connectStyle.css"/>
</head>
        <!--This page should allow users to businesses/developers to view their dashboard and the different options available-->
<body>

    <?php
        include('./includes/dashboardNavbar.inc.php');
    ?>

    <div class="padt-56 h-100p">
        <div class="section h-100p">
            <div class="row h-100p" style="">

                <?php
                    //Load the correct sidebar html depending on what the user account type is
                    include_once('sidebar/'.$userVerifiedData['type'].'Sidebar.php');
                ?>

                <div class="col-sm-12 col-md-7 col-lg-9 pad-30 section-alt" style="">
                    <div id="renderOption" class="scrollable">
                        <!--Content shown here will be determined by the link clicked in the sidebar-->
                        <h2 class="cl-blue-connect">Welcome to the dashboard</h2>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="alertContainer" class="alertPosition disp-none">
        <div class="alert alert-warning alert-dismissible fade show alertError disp-none" role="alert">
            <button type="button" class="close cl-white" data-dismiss="alert" aria-label="Close">
                <span class="alertClose" aria-hidden="true">&times;</span>
            </button>
            <strong>Error</strong>
            <p class="marb-0">
            </p>
        </div>
        <div class="alert alert-success alert-dismissible fade show alertSuccess disp-none" role="alert">
            <button type="button" class="close cl-white" data-dismiss="alert" aria-label="Close">
                <span class="alertClose" aria-hidden="true">&times;</span>
            </button>
            <strong>Success</strong>
            <p class="marb-0">
            </p>
        </div>
    </div>

    <?php 
    //include('./includes/footer.inc.php')
    ?>

</body>
    <script src="/controllers/js/jQuery/jquery.min.js"></script>
    <script src="/controllers/js/tether/tether.min.js"></script>
    <script src="/controllers/js/bootstrap/bootstrap.min.js"></script>
    <script src="/controllers/js/smoothScroll/smoothScroll.js"></script>
    <script src="/controllers/js/navbarController.js"></script>
    <script src="/controllers/js/accessDashboardController.js"></script>
</html>