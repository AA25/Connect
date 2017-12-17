<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connect Business Profile</title>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="/css/connectStyle.css"/>
</head>
<body id="top">

    <?php include('./includes/navBar.inc.php')?>

    <div id="developerProfileContent" class="container padt-56 min-h-770">
        <div class="padt-20 padb-20">
            <div class="row">
                <div class="col-lg-3">
                    <img src="/images/blankProfile.png" alt="" class="bord-rd" width="210" height="205">
                </div>
                <div class="col-lg-6">
                    <h3 id="name" class="cl-blue-connect"></h3>
                    <h5>Business Owner</h5>
                    <div class="row padt-10">
                        <div class="col-lg-6">
                            <p>
                                <span class="cl-blue-connect">Company Name:</span>
                                <br>
                                <span id="busName"></span>
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p>
                                <span class="cl-blue-connect">Company Industry:</span>
                                <br>
                                <span id="busIndustry"></span>
                            </p>
                        </div>
                    </div>
                    <div class="row padt-10">
                        <div class="col-lg-6">
                            <p>
                                <span class="cl-blue-connect">Phone:</span>
                                <br>
                                <span id="phone"></span>
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p>
                                <span class="cl-blue-connect">Email:</span>
                                <br>
                                <span id="email"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 padt-20">
                    <h5 class="cl-blue-connect">Business Description</h5>
                    <p id="businessDescription">
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-12 padt-20">
                    <h5 id="currentProjects" class="cl-blue-connect">Current Projects Open For Developers</h5>
                    <!-- Return the list of projects by this business -->
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
<script src="/controllers/js/businessProfileController.js"></script>
</html>

