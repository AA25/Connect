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
        include('./includes/navBar.inc.php');
        $cookieJWT = new Jwt ($_COOKIE['JWT']);
        $userVerifiedData = $cookieJWT->getDataFromJWT($cookieJWT->token);
        if(!$cookieJWT->verifyJWT($cookieJWT->token)){
            header('Location: http://localhost:8081/index.php');
        }
    ?>

    <div class="padt-56 h-100p">
        <div class="section h-100p">
            <div class="row h-100p" style="">
                <div id="dashboardSidebar" class="col-sm-12 col-md-5 col-lg-3 padt-20 padb-20 padl-30 section-alt sidebar-border-connect" style="">
                    <?php
                        //Load the correct sidebar html depending on what the user account type is
                        include_once('sidebar/'.$userVerifiedData['type'].'Sidebar.php');
                    ?>
                </div>
                <div id="renderOption" class="col-sm-12 col-md-7 col-lg-8 padt-20 padb-20 padl-30" style="">
                    Welcome to the dashboard
                    <!--Content shown here will be determined by the link clicked in the sidebar-->
                </div>
            </div>
        </div>
    </div>

    <?php include('./includes/footer.inc.php')?>

</body>
    <script src="/controllers/js/jQuery/jquery.min.js"></script>
    <script src="/controllers/js/tether/tether.min.js"></script>
    <script src="/controllers/js/bootstrap/bootstrap.min.js"></script>
    <script src="/controllers/js/smoothScroll/smoothScroll.js"></script>
    <script src="/controllers/js/navbarController.js"></script>
    <script src="/controllers/js/accessDashboardController.js"></script>
</html>