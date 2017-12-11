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

        <div class="" style="background-color:black">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <img class="d-block img-fluid" src="/images/try.jpg" alt="First slide" style="display:block;margin:0 auto">
                        <div class="carousel-caption" style="bottom:initial;">
                            <h2>
                                <span class="cl-blue-connect">Connecting</span> Businesses and Developers
                            </h2>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img class="d-block img-fluid" src="/images/try2.jpg" width="" alt="Second slide" style="display:block;margin:0 auto">
                        <div class="carousel-caption" style="bottom:initial;">
                            <h2>
                                <span class="cl-blue-connect">Connecting</span> Businesses and Developers
                            </h2>
                        </div>
                    </div>
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


        <div class="section">
            <div class="container">
                <div id="connectIntro" class="padt-20 padb-20">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <h4 class="cl-blue-connect">
                                What is Connect?
                            </h4>
                            <p>
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                            </p>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <img src="/images/preview.png" alt="" width="370" height="" style="display:block;margin:0 auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-alt">
            <div class="container">
                <div id="dashboardIntro" class="padt-20 padb-20">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6 push-lg-6">
                            <h4 class="cl-blue-connect">
                                The Dashboard
                            </h4>
                            <p>
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                            </p>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 pull-lg-6">
                            <img src="/images/preview.png" alt="" width="370" height="" style="display:block;margin:0 auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div  id="slimMarketplace" class="container">
                <div id="" class="padt-20 padb-20">
                    <div class="txt-ctr padb-20">
                        <h4 class="cl-blue-connect">
                            Project Marketplace
                        </h4>
                        <h5>
                            A short preview of some of the latest projects posted by businesses
                        </h5>
                        <a href="/home/marketplace"> View all Projects </a>
                    </div>
                    <div id="projectMarketplace" class="">
                        <table class="table" style="">
                            <thead>
                            <tr>
                                <th style="min-width:200px;">Location</th>
                                <th style="min-width:230px;">Category</th>
                                <th style="min-width:280px;">Description</th>
                                <th style="min-width:130px;">Budget</th>
                            </tr>
                            </thead>
                            <tbody id="marketplaceTableBody">
                                <tr onclick="window.location='/project/10'">
                                    <td><i class="fa fa-globe" aria-hidden="true"></i> United Kingdom</td>
                                    <td>Backend Development</td>
                                    <td>Many desktop publishing packages and web page editors now use...</td>
                                    <td>$2,500 - 5,000</td>
                                </tr>
                                <tr onclick="window.location='/project/9'">
                                    <td><i class="fa fa-globe" aria-hidden="true"></i> Ireland</td>
                                    <td>Website Creation</td>
                                    <td>rest</td>
                                    <td>€100 - 1,000</td>
                                </tr>
                                <tr onclick="window.location='/project/8'">
                                    <td><i class="fa fa-globe" aria-hidden="true"></i> Ireland</td>
                                    <td>Website Creation</td>
                                    <td>rest</td>
                                    <td>€100 - 1,000</td>
                                </tr>
                                <tr onclick="window.location='/project/5'">
                                    <td><i class="fa fa-globe" aria-hidden="true"></i> United Kingdom</td>
                                    <td>Backend Development</td>
                                    <td>I dont know</td>
                                    <td>£1,000 - 2,500</td>
                                </tr>
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

</html>