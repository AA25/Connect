
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connect Project Description</title>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="/css/connectStyle.css"/>
</head>
<body>

<body id="top">

    <?php include('./includes/navBar.inc.php')?>

    <div id="projectDescription" class="container padt-56 h-100p disp-none">
        <div class="row padt-20 padb-20">
            <div class="col-lg-8 padb-20">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 id="projectName" class="cl-blue-connect">
                        </h3>
                        <p class="marb-0">
                            <span id="projectCategory"></span> 
                            <br>
                            <span id="projectDate"></span>
                        </p>
                    </div>
                    <div class="col-sm-6 txt-right">
                        <h5 class="cl-blue-connect">
                            Budget
                        </h5>
                        <p id="projectBudget">
                        </p>
                    </div>
                </div>
                <div class="padt-20">
                    <h5 id="projectStatus" class="marb-0">
                    </h5>
                </div>
                <div class="padt-20">
                    <h5 class="cl-blue-connect marb-0">
                        Project Description
                    </h5>
                    <p id="projectBio" class="marb-0">
                    </p>
                </div>
                <div class="padt-20">
                    <h5 class="cl-blue-connect marb-0">
                        Project Location
                    </h5>
                    <p id="projectLocation" class="marb-0">
                    </p>
                </div>
                <div class="padt-20">
                    <h5 class="cl-blue-connect marb-0">
                        Required Language
                    </h5>
                    <p id="projectLanguage" class="marb-0">
                    </p>
                </div>
            </div>
            <div id="projectReq" class="col-lg-3 offset-lg-1" style="display:none; background-color:">
                <div class="txt-ctr">
                    <img src="/images/preview.png" alt="" class="bord-rd" width="255">
                </div> 
                <div id="sideContentA" class="txt-ctr">
                    <p id="sideContentMsg" class="padt-20">
                    </p>
                </div>
                <div class="txt-ctr padt-20">
                    <img src="/images/preview.png" alt="" class="bord-rd" width="255">
                </div> 
                <div id="sideContentB" class="txt-ctr">
                    <p id="sideContentMsg" class="padt-20">
                    </p>
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
    </div>

    <div id="notFound" class="container padt-56 h-100p disp-none">
        <h3 class="cl-blue-connect mart-20">This project was not found</h3>
    </div>

    <div class="modal fade" id="pitchNowModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header navbar-bg">
                    <h5 class="modal-title cl-blue-connect" id="">Connect</h5>
                    <button type="button" class="close cl-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" id="projectRequestForm">
                        <div>
                            <div class="">
                                <label for="devMsg">Why you should be chosen for this project:</label><br>
                                <textarea rows="10" cols="56" maxlength="500" placeholder="Enter a personalised message to the business owner as to why they should accept your request to join this project " id="devMsg" name="devMsg" maxlength="500"></textarea>
                            </div>
                            <div>
                                <div>
                                    <h6 id="msgCount"></h6>
                                </div>
                                <div class="txt-ctr">
                                    <button type="submit" class="btn cl-white btn-success">
                                        Pitch Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
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
    <script src="/controllers/js/projectDescController.js"></script>
</html>

