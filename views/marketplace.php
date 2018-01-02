<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connect Marketplace</title>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="/css/connectStyle.css"/>
    <!-- <link rel="icon" href="images/favicon.ico" type="image/x-icon"> -->
</head>
<body id="top">

    <?php include('./includes/navBar.inc.php')?>

    <div id="marketplace" class="disp-none">
        <div class="section padt-80 padb-40">
            <div id="marketplace" class="container disp-none pad-0 disp-bl">
                <div id="" class="">
                    <div class="padl-10 padr-10 padb-40 row">
                        <div class="col-lg-8">
                            <h3 class="cl-blue-connect">
                                Project Marketplace
                            </h3>
                            <h6>
                                The newest projects that have been submitted by Businesses and a currently looking for developers to join!
                            </h6>
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="txt-right">
                                        <form class="form-inline" method="get" action="/">
                                            <i class="fa fa-search cl-blue-connect marr-10" aria-hidden="true"></i>
                                            <input id="search-input" class="form-control input-lg" placeholder="Search projects by project name" onkeyup="showProjectSuggestions(this.value)" style="width:270px">
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div id="livesearch" class="disp-none mart-5 pad-10 bord-rd" style="border:1px solid rgba(0,0,0,.15);">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="projectMarketplace" class="">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="min-w-200">Location</th>
                                <th class="min-w-230">Category</th>
                                <th class="min-w-280">Description</th>
                                <th class="min-w-130">Budget</th>
                            </tr>
                            </thead>
                            <tbody id="marketplaceTableBody" class="pointer">
                            </tbody>
                        </table>
                    </div>
                    <div class="txt-ctr padt-20">
                        <button type="business" class="btn cl-white bg-cl-blue-connect pointer" onclick="loadMore()">
                            Load More Projects
                        </button>
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

    </div>

</body>
    <script src="controllers/js/jQuery/jquery.min.js"></script>
    <script src="controllers/js/tether/tether.min.js"></script>
    <script src="controllers/js/bootstrap/bootstrap.min.js"></script>
    <script src="controllers/js/smoothScroll/smoothScroll.js"></script>
    <script src="controllers/js/navbarController.js"></script>
    <script src="controllers/js/marketplaceController.js"></script>
</html>