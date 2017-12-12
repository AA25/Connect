<?php 
// require __DIR__."/includes/init.inc.php"; 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connect Homepage</title>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="/css/connectStyle.css"/>
    <!-- <link rel="icon" href="images/favicon.ico" type="image/x-icon"> -->
</head>

<body id="top">

    <?php include('./includes/navBar.inc.php')?>

    <div id="registerContent" class="padt-56">

        <div class="section-alt padb-40">
            <div class="container">
                <div class="padt-40 padb-60" style="">
                    <div class="padb-20">
                        <h5 class="cl-blue-connect">
                            Register Your Business 
                        </h5>
                        <h6>
                            And be one step closer to listing your project on the marketplace
                        </h6>
                    </div>
                    <form method="post" action="" id="registerBusForm" class="">
                        <div class="pad-30 marb-30 bg-cl-white bord-rd" style="border-style: solid; border-width:1px; border-color:#1a1a1a;">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="username">Username:</label>
                                    <input type="text" class="form-control" placeholder="Enter your username" name="username" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="firstName">First Name:</label>
                                    <input type="text" class="form-control" placeholder="Enter your name" name="firstName" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="lastName">Last Name:</label>
                                    <input type="text" class="form-control" placeholder="Enter your last name" name="lastName" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="password">password:</label>
                                    <input type="password" class="form-control" placeholder="Enter password" name="password" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="email">email:</label>
                                    <input type="email" class="form-control" placeholder="Enter email" name="email" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="phone">Phone number:</label><br>
                                    <input type="phone" class="form-control" placeholder="Enter phone number" name="phone" required>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="pad-30 marb-20 bg-cl-white bord-rd" style="border-style: solid; border-width:1px; border-color:#1a1a1a;">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="busName">Company Name:</label>
                                    <input type="text" class="form-control" placeholder="Enter your companies name" name="busName" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="busIndustry">Industry:</label>
                                    <input type="text" class="form-control" placeholder="Your companies industry" name="busIndustry" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="busBio">Companies Bio:</label>
                                    <textarea class="form-control" id="busBio" rows="8" placeholder="Enter a description of your company" name="busBio" required></textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn cl-white bg-cl-blue-connect pull-right" style="">Register Your Business</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <?php include('./includes/footer.inc.php')?>

</body>

<script src="../controllers/js/jQuery/jquery.min.js"></script>
<script src="../controllers/js/bootstrap/bootstrap.min.js"></script>
<script src="../controllers/js/smoothScroll/smoothScroll.js"></script>
<script src="../controllers/js/navBar.js"></script>
<script src="../controllers/js/registerController.js"></script>
</html>