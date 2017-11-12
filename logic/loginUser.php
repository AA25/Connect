<?php 
    //TODO Login and logout should not be an api but be placed in the logic folder
    require "../includes/init.inc.php";
    $pdo = get_db();

    //important to tell your browser what we will be sending
    // header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $loginJSON = json_decode(file_get_contents('php://input'),true);
    
    if (!empty($loginJSON['email']) && !empty($loginJSON['password']) && !empty($loginJSON['location'])){
        //Attempt to retrieve user details from provided login details and then prepares sql query
        if($loginJSON['location'] == 'developers'){
            $r = prepDevSQL($pdo);
        }elseif($loginJSON['location'] == 'businesses'){
            $r = prepBusSQL($pdo);
        }else{
            //Where location isnt developers or businesses
            echo json_encode(array('Error' => 'Error'));
            break;
        }

        $r->execute([
            'email' => $loginJSON['email'],
            'password' => $loginJSON['password']
        ]);

        //If num of rows returned is greater than 0 we know we have a result
        if($r->rowCount() > 0){
            $userInfo = [];
            $userInfo['Success'] = 'Successful login';
            foreach($r as $info){
                if($loginJSON['location'] == 'developers'){
                    pushDevDetails($userInfo, $info);
                }elseif($loginJSON['location'] == 'businesses'){
                    pushBusDetails($userInfo, $info);        
                }
            }

            //$userToken = createJWT($userInfo);
            $userToken = new Jwt('');
            $userToken->setToken($userInfo);
            $value = $userToken->getToken();
            setUserCookies($userInfo, $loginJSON['location'], $userToken->getToken());
            echo json_encode(array('Success' => 'Successful login'));
        }else{
            echo json_encode(array('Error' => 'Incorrect login details'));
        }
    }else{
        echo json_encode(array('Error' => 'Empty Fields'));
    }

    function prepDevSQL(&$pdo){
        return $pdo->prepare(
            "select firstName, lastName, dob, languages, email, devBio, phone, type from developers where email = :email and password = :password"
        );  
    }

    function prepBusSQL(&$pdo){
        return $pdo->prepare(
            "select busName, busIndustry, busBio, firstName, lastName, email, phone, type from businesses where email = :email and password = :password"
        );  
    }

    function pushDevDetails(&$userInfo, $info){
        $userInfo['firstName'] = $info['firstName']; $userInfo['lastName'] = $info['lastName']; $userInfo['dob'] = $info['dob'];
        $userInfo['languages'] = $info['languages']; $userInfo['email'] = $info['email']; $userInfo['devBio'] = $info['devBio'];
        $userInfo['phone'] = $info['phone']; $userInfo['type'] = $info['type'];
    }
    function pushBusDetails(&$userInfo, $info){
        $userInfo['busName'] = $info['busName']; $userInfo['busIndustry'] = $info['busIndustry']; $userInfo['busBio'] = $info['busBio'];
        $userInfo['firstName'] = $info['firstName']; $userInfo['lastName'] = $info['lastName']; $userInfo['email'] = $info['email'];
        $userInfo['phone'] = $info['phone']; $userInfo['type'] = $info['type'];
    }

    //TODO Remember to return back users details so it can be saved in the local storage or if you want we can use REDIS and store the data there!
    function setUserCookies($userInfo,$location,$userToken){
        $cookiePath = "/";
        $cookieExp = time()+(60*60*24);//one day -> seconds*minutes*hours
        if($location == 'developers'){
            setrawcookie('JWT', $userToken, $cookieExp, $cookiePath);  
            // setrawcookie('firstName', $userInfo['firstName'], $cookieExp, $cookiePath);
            // setrawcookie('lastName', $userInfo['lastName'], $cookieExp, $cookiePath);  
            // setrawcookie('dob', $userInfo['dob'], $cookieExp, $cookiePath);  
            // setrawcookie('languages', $userInfo['languages'], $cookieExp, $cookiePath);  
            // setrawcookie('email', $userInfo['email'], $cookieExp, $cookiePath);    
            // setrawcookie('password', $userInfo['password'], $cookieExp, $cookiePath);  
            // setrawcookie('devBio', $userInfo['devBio'], $cookieExp, $cookiePath);  
            // setrawcookie('phone', $userInfo['phone'], $cookieExp, $cookiePath);  
            // setrawcookie('type', $userInfo['type'], $cookieExp, $cookiePath); 
        }elseif($location == 'businesses'){
            setrawcookie('JWT', $userToken, $cookieExp, $cookiePath);  
            // setrawcookie('busName', $userInfo['busName'], $cookieExp, $cookiePath);
            // setrawcookie('busIndustry', $userInfo['busIndustry'], $cookieExp, $cookiePath);  
            // setrawcookie('busBio', $userInfo['busBio'], $cookieExp, $cookiePath);  
            // setrawcookie('firstName', $userInfo['firstName'], $cookieExp, $cookiePath);  
            // setrawcookie('lastName', $userInfo['lastName'], $cookieExp, $cookiePath);    
            // setrawcookie('password', $userInfo['password'], $cookieExp, $cookiePath);  
            // setrawcookie('email', $userInfo['email'], $cookieExp, $cookiePath);  
            // setrawcookie('phone', $userInfo['phone'], $cookieExp, $cookiePath);  
            // setrawcookie('type', $userInfo['type'], $cookieExp, $cookiePath); 
        }
    }
?>


