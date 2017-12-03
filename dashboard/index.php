<?php 
    require "../includes/init.inc.php";
    $cookieJWT = new Jwt ($_COOKIE['JWT']);
    $userVerifiedData = $cookieJWT->getDataFromJWT($cookieJWT->token);  
    if(!$cookieJWT->verifyJWT($cookieJWT->token)){
        header('Location: http://localhost:8081/index.php');    
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connects Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="../../css/connectStyle.css"/>
</head>
        <!--This page should allow users to businesses/developers to view their dashboard and the different options available-->
<body>

    <div id="pseudoContainer" class="">
        <div id="dashboardSidebar" class="col-md-3">
            <?php 
                //Load the correct sidebar html depending on what the user account type is
                include_once('includes/'.$userVerifiedData['type'].'Sidebar.php');
            ?>
        </div>
        <div id="renderOption" class="col-md-8" style="background-color:none">
            Welcome to the dashboard
            <!--Content shown here will be determined by the link clicked in the sidebar-->
        </div>
    </div>
    

</body>
    <script src="../js/jQuery/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="../js/smoothScroll/smoothScroll.js"></script>
    <script src="../js/navBar.js"></script>
    <script src="../js/accessDashboard.js"></script>
</html>