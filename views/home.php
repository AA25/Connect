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

    <div id="homepageContent" class="padt-56">

        <div class="bg-cl-black">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <!-- <li data-target="#carouselExampleIndicators" data-slide-to="1"></li> -->
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <img class="d-block img-fluid slider-center" src="/images/try2.jpg" alt="First slide">
                        <div class="carousel-caption bt-ini">
                            <h3>
                                <span class="cl-blue-connect">Connecting</span> Businesses and Developers
                            </h3>
                        </div>
                    </div>
                    <!-- <div class="carousel-item">
                        <img class="d-block img-fluid slider-center" src="/images/try1.jpg" width="" alt="Second slide">
                        <div class="carousel-caption bt-ini">
                            <h2>
                                <span class="cl-blue-connect">Connecting</span> Businesses and Developers
                            </h2>
                        </div>
                    </div> -->
                </div>
                <!-- <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a> -->
            </div>
        </div>


        <div class="section padt-40 padb-40">
            <div class="container">
                <div id="connectIntro">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <h5 class="cl-blue-connect">
                                What is Connect?
                            </h5>
                            <p class="marb-0">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                            </p>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <img src="/images/preview.png" alt="" width="370" height="" class="slider-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-alt padt-40 padb-40">
            <div class="container">
                <div id="dashboardIntro" class="">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6 push-lg-6">
                            <h5 class="cl-blue-connect">
                                The Dashboard
                            </h5>
                            <p class="marb-0">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                            </p>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 pull-lg-6">
                            <img src="/images/preview.png" alt="" width="370" height="" class="slider-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section padt-40 padb-40">
            <div  id="slimMarketplace" class="container disp-none">
                <div id="" class="">
                    <div class="txt-ctr padb-40">
                        <h5 class="cl-blue-connect">
                            Project Marketplace
                        </h5>
                        <h6>
                            A short preview of some of the latest projects posted by businesses
                        </h6>
                        <a href="/home/marketplace"> View all Projects </a>
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
                            <tbody id="slimMarketplaceTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('./includes/footer.inc.php')?>

</body>

<script src="/js/jQuery/jquery.min.js"></script>
<script src="/js/bootstrap/bootstrap.min.js"></script>
<script src="/js/smoothScroll/smoothScroll.js"></script>
<script src="/js/navBar.js"></script>
<script src="/js/homepageController.js"></script>
</html>