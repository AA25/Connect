<?php
    require './controllers/php/checkLoginController.php';
    $cookieJWT = new Jwt ($_COOKIE['JWT']);
    $userVerifiedData = $cookieJWT->getDataFromJWT($cookieJWT->token);
    if(!$cookieJWT->verifyJWT($cookieJWT->token)){
        //If the user is not an authenticated user then they will be redirected to the home page
        header('Location: http://localhost:8081/home');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connect Register Project</title>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="/css/connectStyle.css"/>
</head>
<body>

    <?php
        include('./includes/dashboardNavbar.inc.php');
    ?>

    <div id="registerProject" class="padt-56">
        <div class="section-alt padb-40">
            <div class="container">
                <div class="padt-40 padb-60" style="">
                    <div class="padb-20">
                        <h5 class="cl-blue-connect">
                            Register Your Project 
                        </h5>
                        <h6>
                            And be one step closer to kickstarting your project on the marketplace
                        </h6>
                    </div>

                    <form method="post" action="" id="registerProjectForm">
                        <div class="pad-30 padb-50 marb-30 bg-cl-white bord-rd" style="border-style: solid; border-width:1px; border-color:#1a1a1a;">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="projectName">Project Name:</label>
                                    <input type="text" class="form-control" placeholder="Enter a project name" name="projectName" maxlength="45" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="projectCategory">Project Category:</label><br>
                                    <select name="projectCategory" required>
                                        <option disabled selected value> Select the category that fits your project</option>
                                        <option value="Website Creation">Website Creation</option>
                                        <option value="Web Application">Web Application</option>
                                        <option value="Backend Development">Backend Development</option>
                                        <option value="FrontEnd Development">FrontEnd Development</option>
                                        <option value="UX/UI Development">UX/UI Development</option>
                                        <option value="Data Analysing">Data Analysing</option>
                                        <option value="Machine Learning">Machine Learning</option>
                                    </select> 
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="projectCountry">Project Country:</label><br>
                                    <select name="projectCountry" required>
                                        <option disabled selected value> Select the country location of your project</option>
                                        <option value="Ireland">Ireland</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="Spain">Spain</option>
                                        <option value="Italy">Italy</option>
                                        <option value="Germany">Germany</option>
                                        <option value="Netherlands">Netherlands</option>
                                        <option value="China">China</option>
                                        <option value="China">United States</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="projectCurrency">Project Currency:</label><br>
                                    <select name="projectCurrency" required>
                                        <option disabled selected value> Select the appropriate currency</option>
                                        <option value="€">Euro</option>
                                        <option value="£">Pound</option>
                                        <option value="$">Dollar</option>
                                        <option value="¥">Yen</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="projectBudget">Project Budget:</label><br>
                                    <select name="projectBudget" required>
                                        <option disabled selected value> Select your budget range </option>
                                        <option value="100 - 1,000">100 - 1,000</option>
                                        <option value="1,000 - 2,500">1,000 - 2,500</option>
                                        <option value="2,500 - 5,000">2,500 - 5,000</option>
                                        <option value="5,000 - 10,000">5,000 - 10,000</option>
                                        <option value="10,000+">10,000+</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="projectLanguage">Enter a language required for this project</label>
                                    <select name="projectLanguage" required>
                                        <option disabled selected value> Select the main language required </option>
                                        <option value="English">English</option>
                                        <option value="Irish">Irish</option>
                                        <option value="Italian">Italian</option>
                                        <option value="Spanish">Spanish</option>
                                        <option value="German">German</option>
                                        <option value="Japanese">Japanese</option>
                                        <option value="Chinese">Chinese</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="projectBio">Project Bio:</label>
                                    <textarea id="projectBio" class="form-control" rows="8" placeholder="Enter a description of what this project involves" name="projectBio" maxlength="500" required></textarea>
                                    <div>
                                        <span class="fs-14" id="projectBioCount"></spanh6>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="">
                                <label for="projectDeadline">Project Deadline:</label>
                                <input type="text" class="form-control" placeholder="Enter a deadline date for the project" name="projectDeadline">
                            </div> -->
                            <button type="submit" class="btn cl-white bg-cl-blue-connect pull-right pointer" style="">Register Project</button>

                        </div>
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

    <div id="successfulProject" class="padt-56 disp-none">
        <div class="section-alt padb-40">
            <div class="container">
                <div class="padt-40 padb-60 min-h-770">
                    <div class="txt-ctr">
                        <h3 class="cl-blue-connect">
                            Project created
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </h3>
                        <p>
                            Your new project has been successfully created.
                        </p>
                        <p>
                            View your project in "Manage projects" in the Dashboard. <br>
                            Developers can now see your project in the marketplace and send requests.
                        </p>
                    </div>
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
    <script src="/controllers/js/registerProject.js"></script>
</html>