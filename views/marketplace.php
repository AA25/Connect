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

    <div id="marketplace" style="display:none">
        <div class="section padt-80 padb-40">
            <div id="marketplace" class="container disp-none pad-0" style="display: block;">
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
                            <div class="txt-right">
                                <form class="form-inline" method="get" action="/">
                                    <i class="fa fa-search cl-blue-connect marr-10" aria-hidden="true"></i>
                                    <input id="search-input" class="form-control input-lg" placeholder="Search projects by project name" style="width:270px">
                                </form>
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
                            <tbody id="marketplaceTableBody">
                            <tr onclick="window.location='/project/11'">
                                <!-- <td><i class="fa fa-globe" aria-hidden="true"></i> United Kingdom</td><td>Website Creation</td><td>Alice was here</td><td>£100 - 1,000</td></tr><tr onclick="window.location='/project/10'"><td><i class="fa fa-globe" aria-hidden="true"></i> United Kingdom</td><td>Backend Development</td><td>rest</td><td>$2,500 - 5,000</td></tr><tr onclick="window.location='/project/9'"><td><i class="fa fa-globe" aria-hidden="true"></i> Ireland</td><td>Website Creation</td><td>rest</td><td>€100 - 1,000</td></tr><tr onclick="window.location='/project/8'"><td><i class="fa fa-globe" aria-hidden="true"></i> Ireland</td><td>Website Creation</td><td>rest</td><td>€100 - 1,000</td></tr><tr onclick="window.location='/project/5'"><td><i class="fa fa-globe" aria-hidden="true"></i> United Kingdom</td><td>Backend Development</td><td>I dont know</td><td>£1,000 - 2,500</td></tr><tr onclick="window.location='/project/4'"><td><i class="fa fa-globe" aria-hidden="true"></i> United Kingdom</td><td>Backend Development</td><td>Richard McClintock, a Latin professor at Hampden-Sydney Colle...</td><td>£5,000 - 10,000</td></tr></tbody> -->
                        </table>
                    </div>
                    <div class="txt-ctr padt-20">
                        <button type="business" class="btn cl-white bg-cl-blue-connect" onclick="loadMore()">
                            Load More Projects
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- <div id="marketplace" style="display:none">
        <div id="projectMarketplace">
            <table class="table">
                <thead>
                <tr>
                    <th>Location</th>
                    <th>Project Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Budget</th>
                </tr>
                </thead>
                <tbody id="marketplaceTableBody"></tbody>
            </table>
        </div>
        <button onclick="loadMore()">
            LOAD
        </button>
    </div> -->

</body>
    <script src="controllers/js/jQuery/jquery.min.js"></script>
    <script src="controllers/js/tether/tether.min.js"></script>
    <script src="controllers/js/bootstrap/bootstrap.min.js"></script>
    <script src="controllers/js/smoothScroll/smoothScroll.js"></script>
    <script src="controllers/js/navBar.js"></script>
    <script src="controllers/js/marketplaceController.js"></script>
</html>