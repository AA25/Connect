<?php 
    require "../../includes/init.inc.php";
    $pdo = get_db();

    //important to tell your browser what we will be sending
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $loginJSON = json_decode(file_get_contents('php://input'),true);

    $validationCheck = new ServerValidation();
    
    if ($validationCheck->loginSanitisation(
        $loginJSON['email'],$loginJSON['password'],$loginJSON['location']
    )){
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
        ]);

        $correctDetails = true;

        //If num of rows returned is greater than 0 we know the account exists
        if($r->rowCount() > 0){
            $userInfo = [];
            $userInfo['Success'] = 'Successful login';
            foreach($r as $info){
                //But first we check if the password given matches the hashed password for the account
                if(password_verify($loginJSON['password'], $info['password'])){
                    //Then continue to push the project details to the userInfo array 
                    if($loginJSON['location'] == 'developers'){
                        pushDevDetails($userInfo, $info);
                    }elseif($loginJSON['location'] == 'businesses'){
                        pushBusDetails($userInfo, $info);
                    }
                }else{
                    $correctDetails = false;
                    break;
                }
            }

            if($correctDetails){
                //Create a new token object that will be the token we return to the user
                $userToken = new Jwt('');
                //The token will contain some of the basic user details
                $userToken->setToken($userInfo);
                //Then return the token string that needs to be attached to future requests
                $value = $userToken->getToken();
                setUserCookies($userInfo, $loginJSON['location'], $userToken->getToken());
                echo json_encode(array('Success' => 'Successful login'));
            }else{
                //Passwords did not match
                echo json_encode(array('Error' => 'Incorrect login details'));                
            }

        }else{
            //Account does not exist
            echo json_encode(array('Error' => 'Incorrect login details'));
        }
    }else{
        echo json_encode(array('Error' => 'Validation Failure'));
    }

    function prepDevSQL(&$pdo){
        //Prepare statement to return developer details
        return $pdo->prepare(
            "select username, firstName, lastName, dob, languages, email, devBio, phone, type, password from developers where email = :email"
        );  
    }

    function prepBusSQL(&$pdo){
        //Prepare statement to return business details
        return $pdo->prepare(
            "select busName, busIndustry, busBio, username, firstName, lastName, email, phone, type, password from businesses where email = :email"
        );  
    }

    function pushDevDetails(&$userInfo, $info){
        //Push user details to the array
        $userInfo['username'] = $info['username']; $userInfo['firstName'] = $info['firstName']; $userInfo['lastName'] = $info['lastName']; 
        $userInfo['dob'] = $info['dob']; $userInfo['languages'] = $info['languages']; $userInfo['email'] = $info['email']; $userInfo['devBio'] = $info['devBio'];
        $userInfo['phone'] = $info['phone']; $userInfo['type'] = $info['type'];
    }
    function pushBusDetails(&$userInfo, $info){
    //Push user details to the array
        $userInfo['busName'] = $info['busName']; $userInfo['busIndustry'] = $info['busIndustry']; $userInfo['busBio'] = $info['busBio'];
        $userInfo['username'] = $info['username']; $userInfo['firstName'] = $info['firstName']; $userInfo['lastName'] = $info['lastName']; 
        $userInfo['email'] = $info['email']; $userInfo['phone'] = $info['phone']; $userInfo['type'] = $info['type'];
    }

    function setUserCookies($userInfo,$location,$userToken){
        //Set a cookie containing the user token
        $cookiePath = "/";
        $cookieExp = time()+(60*60*24);//one day -> seconds*minutes*hours
        if($location == 'developers'){
            setrawcookie('JWT', $userToken, $cookieExp, $cookiePath);

        }elseif($location == 'businesses'){
            setrawcookie('JWT', $userToken, $cookieExp, $cookiePath);
        }
    }
?>


