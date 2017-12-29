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
                            Register as a Developer
                        </h5>
                        <h6>
                            And be one step closer to sharing your skills with businesses around the world
                        </h6>
                    </div>
                    
                    <form method="post" action="" id="registerDevForm" class="">
                        <div class="pad-30 marb-30 bg-cl-white bord-rd" style="border-style: solid; border-width:1px; border-color:#1a1a1a;">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="username" class="">username:</label>
                                    <input type="text" class="form-control" placeholder="Enter your username" name="username" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" placeholder="Enter email" name="email" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="pwd">Password:</label>
                                    <input type="password" class="form-control" placeholder="Enter password" name="password" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="phone">Phone Number:</label>
                                    <input type="phone" class="form-control" placeholder="Enter phone number" name="phone" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="firstName">First Name:</label>
                                    <input type="text" class="form-control" placeholder="Enter first name" name="firstName" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="dob">Date of Birth:</label>
                                    <input type="date" class="form-control" placeholder="Enter D.O.B" name="dob" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="lastName">Last Name:</label>
                                    <input type="text" class="form-control" placeholder="Enter last name" name="lastName" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="languages">Languages Spoken:</label>
                                    <select multiple class="form-control languages" name="languages" required>
                                        <option value="English">English</option>
                                        <option value="Irish">Irish</option>
                                        <option value="Chinese">Chinese</option>
                                        <option value="Spanish">Spanish</option>
                                        <option value="French">French</option>
                                        <option value="German">German</option>
                                        <option value="Italian">Italian</option>
                                        <option value="Japanese">Japanese</option>
                                    </select>
                                    <span class="fs-11">Hold CTRL (on Windows) or CMD (on Mac) to select multiple languages</span>
                                </div>
                            </div>
                        </div>
                        <div class="pad-30 marb-20 bg-cl-white bord-rd" style="border-style: solid; border-width:1px; border-color:#1a1a1a;">
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label>Developer Bio:</label><br>
                                    <textarea id="devBio" class="form-control" rows="4" cols="50" name="devBio" placeholder="Describe yourself here..." required></textarea>
                                    <div>
                                        <span class="fs-14" id="msgCount"></spanh6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn cl-white bg-cl-blue-connect pull-right pointer" style="">Register</button>
                    </form>

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
                <button type="button" class="close cl-white pointer" data-dismiss="alert" aria-label="Close">
                    <span class="alertClose" aria-hidden="true">&times;</span>
                </button>
                <strong>Success</strong>
                <p class="marb-0">
                </p>
            </div>
        </div>
    </div>

    <div id="successfulReg" class="padt-56">
            <div class="section-alt padb-40">
                <div class="container">
                    <div class="padt-40 padb-60 min-h-770">
                        <div class="txt-ctr">
                            <h3 class="cl-blue-connect">
                                Successful Registeration
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </h3>
                            <p>
                                Your account has been successfully registered.
                            </p>
                            <p>
                                You can now log into your account using the navigation bar at the top.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php include('./includes/footer.inc.php')?>

</body>

<script src="../controllers/js/jQuery/jquery.min.js"></script>
<script src="../controllers/js/tether/tether.min.js"></script>
<script src="../controllers/js/bootstrap/bootstrap.min.js"></script>
<script src="../controllers/js/smoothScroll/smoothScroll.js"></script>
<script src="../controllers/js/navbarController.js"></script>
<script src="../controllers/js/registerController.js"></script>
</html>