
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Connect Project Description</title>
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../css/font-awesome-4.7.0/css/font-awesome.min.css"/>
        <link rel="stylesheet" type="text/css" href="../css/connectStyle.css"/>
    </head>
    <body>

    <div>
        <div id="projectReq" style="display:none;">
            Pitch now to send a request to join this project
            <button data-toggle="modal" data-target="#myModal">
                Pitch Now
            </button>

            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Modal Header</h4>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="" id="projectRequestForm">
                                <div>
                                    <div class="">
                                        <label for="devMsg">Why you should be chosen for this project:</label><br>
                                        <textarea rows="10" cols="78" placeholder="Enter a personalised message to the business owner as to why they should accept your request to join this project " id="devMsg" name="devMsg" maxlength="500"></textarea>
                                    </div>
                                    <div>
                                        <div>
                                            <h6 id="msgCount"></h6>
                                        </div>
                                        <div>
                                            <button type="submit" class="" style="">Pitch </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
    <script src="../js/jQuery/jquery.min.js"></script>
    <script src="../js/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="../js/smoothScroll/smoothScroll.js"></script>
    <script src="../js/navBar.js"></script>
    <script src="../js/projectDesc.js"></script>
</html>

