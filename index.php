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
    <!-- <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> -->
    <link rel="stylesheet" type="text/css" href="css/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/connectStyle.css"/>
    <!-- <link rel="icon" href="images/favicon.ico" type="image/x-icon"> -->
</head>
<body>
    <?php 
        // $db = get_db();
        //var_dump($db);
    ?>
    <!-- <form method="post" action="" id="registerDevForm" class="">
        <div class="">
            <label for="firstName">First Name:</label>
            <input type="text" class="form-control" placeholder="Enter first name" name="firstName" >
        </div>
        <div class="">
            <label for="lastName">Last Name:</label>
            <input type="text" class="form-control" placeholder="Enter last name" name="lastName" >
        </div>
        <div class="">
            <label for="dob">Date of Birth:</label>
            <input type="text" class="form-control" placeholder="Enter D.O.B" name="dob" >
        </div>
        <div class="">
            <label for="languages">Languages</label>
            <input type="text" class="form-control" placeholder="Enter languages separated by a , " name="languages" >
        </div>
        <div class="">
            <label for="phone">Phone Number:</label>
            <input type="text" class="form-control" placeholder="Enter phone number" name="phone" >
        </div>
        <div class="">
            <label for="email">Email:</label>
            <input type="email" class="form-control" placeholder="Enter email" name="email" >
        </div>
        <div class="">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" placeholder="Enter password" name="password" >
        </div>
        <div class="">
            <label>Developer Bio:</label><br>
            <textarea rows="4" cols="50" name="devBio" placeholder="Describe yourself here..." ></textarea>
        </div>
        <button type="submit" class="" style="">Register</button>
    </form> -->
    
    <!-- <form method="post" action="" id="registerBusForm" class="">
        <div class="">
            <label for="busName">Company Name:</label>
            <input type="text" class="form-control" placeholder="Enter your companies name" name="busName" >
        </div>
        <div class="">
            <label for="busIndustry">Industry:</label>
            <input type="text" class="form-control" placeholder="Your companies industry" name="busIndustry" >
        </div>
        <div class="">
            <label for="busBio">Companies Bio:</label><br>
            <textarea rows="4" cols="50" placeholder="Enter a description of your company" name="busBio"></textarea>
        </div>
        <div class="">
            <label for="firstName">First Name:</label>
            <input type="text" class="form-control" placeholder="Enter your name" name="firstName" >
        </div>
        <div class="">
            <label for="lastName">Last Name:</label>
            <input type="text" class="form-control" placeholder="Enter your last name" name="lastName" >
        </div>
        <div class="">
            <label for="password">password:</label>
            <input type="password" class="form-control" placeholder="Enter password" name="password" >
        </div>
        <div class="">
            <label for="email">email:</label>
            <input type="email" class="form-control" placeholder="Enter email" name="email" >
        </div>
        <div class="">
            <label for="phone">Phone number:</label><br>
            <input type="text" class="form-control" placeholder="Enter phone number" name="phone" >
        </div>
        <button type="submit" class="" style="">Register</button>
    </form> -->
    
    <button onclick="logOut()">
        Logout
    </button>

    <form method="post" action="" id="loginDevForm">
        <div>
            <label for="email">Email: </label>
            <input type="text" class="" placeholder="Enter email address" name="email">
        </div>
        <div>
            <label for="password">Password: </label>
            <input type="password" class="" placeholder="Enter password" name="password">
        </div>
        <input id="loginBtn" type="submit" name="Submit" value="Log In">
    </form>
    
    <form method="post" action="" id="loginBusForm">
        <div>
            <label for="email">Email: </label>
            <input type="text" class="" placeholder="Enter email address" name="email">
        </div>
        <div>
            <label for="password">Password: </label>
            <input type="password" class="" placeholder="Enter password" name="password">
        </div>
        <input id="loginBtn" type="submit" name="Submit" value="Log In">
    </form>

    <a href="/views/myaccount.php">My Account</a>
    
</body>
    <script src="js/jQuery/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="js/smoothScroll/smoothScroll.js"></script>
    <script src="js/navBar.js"></script>
    <!-- <script src="js/core.js"></script>
    <script src="js/enc-base64.js"></script>
    <script src="js/hmac.js"></script>
    <script src="js/sha256.js"></script>
    <script src="js/tokenAuth.js"></script> -->
</html>