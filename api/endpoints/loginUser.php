<?php 
    require "../../includes/init.inc.php";
    $pdo = get_db();

    // important to tell your browser what we will be sending
    // header("Access-Control-Allow-Origin: *");
    // header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $loginJSON = json_decode(file_get_contents('php://input'),true);
    
    if (!empty($loginJSON['email']) && !empty($loginJSON['password']) && !empty($loginJSON['location'])){
        //Attempt to retrieve user details from provided login details and then prepares sql query
        if($loginJSON['location'] == 'developers'){
            $r = prepDevSQL($pdo);
        }elseif($loginJSON['location'] == 'businesses'){
            $r = prepBusSQL($pdo);
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
            $userToken = createJWT($userInfo);
            //echo json_encode(array('Success' => $userToken));
            setUserCookies($userInfo,$loginJSON['location'], $userToken);
            echo json_encode(array('Success' => 'Successful login'));
        }else{
            echo json_encode(array('Error' => 'Incorrect login details'));
        }
    }else{
        echo json_encode(array('Error' => 'Empty Fields'));
    }

    function prepDevSQL(&$pdo){
        return $pdo->prepare(
            "select firstName, lastName, dob, languages, email, devBio, phone from developers where email = :email and password = :password"
        );  
    }

    function prepBusSQL(&$pdo){
        return $pdo->prepare(
            "select busName, busIndustry, busBio, firstName, lastName, email, phone from businesses where email = :email and password = :password"
        );  
    }

    function pushDevDetails(&$userInfo, $info){
        $userInfo['firstName'] = $info['firstName']; $userInfo['lastName'] = $info['lastName']; $userInfo['dob'] = $info['dob'];
        $userInfo['languages'] = $info['languages']; $userInfo['email'] = $info['email']; $userInfo['devBio'] = $info['devBio'];
        $userInfo['phone'] = $info['phone'];
    }
    function pushBusDetails(&$userInfo, $info){
        $userInfo['busName'] = $info['busName']; $userInfo['busIndustry'] = $info['busIndustry']; $userInfo['busBio'] = $info['busBio'];
        $userInfo['firstName'] = $info['firstName']; $userInfo['lastName'] = $info['lastName']; $userInfo['email'] = $info['email'];
        $userInfo['phone'] = $info['phone'];
    }

    function createJWT($userInfo){
        $userInfoJSON = json_encode($userInfo);
        // JWT token structure is like header.payload.signature
        // The header of the JWT token
        $headerEnc = base64_encode('{"alg": "HS256","typ": "JWT"}');

        // The payload of the JWT token
        $payloadEnc = base64_encode($userInfoJSON);
        // $payloadEnc = base64_encode(
        // //'{"iss": "connectServer","name": "Ade"}'
        // );

        // header and payload concat
        $headerPayload = $headerEnc . '.' . $payloadEnc;

        //Setting the secret key
        $secretKey = 'ademola';

        // Creating the signature, a hash with the sha256 algorithm and the secret key
        $signature = base64_encode(hash_hmac('sha256', $headerPayload, $secretKey, true));

        // Creating the JWT token
        $jwtToken = $headerPayload . '.' . $signature;

        return $jwtToken;
    }

    function setUserCookies($userInfo,$location,$userToken){
        $cookiePath = "/";
        $cookieExp = time()+(60*60*24);//one day -> seconds*minutes*hours
        if($location == 'developers'){
            setcookie('JWT', $userToken, $cookieExp, $cookiePath);  
            setcookie('firstName', $userInfo['firstName'], $cookieExp, $cookiePath);
            setcookie('lastName', $userInfo['lastName'], $cookieExp, $cookiePath);  
            setcookie('dob', $userInfo['dob'], $cookieExp, $cookiePath);  
            setcookie('languages', $userInfo['languages'], $cookieExp, $cookiePath);  
            setcookie('email', $userInfo['email'], $cookieExp, $cookiePath);    
            setcookie('password', $userInfo['password'], $cookieExp, $cookiePath);  
            setcookie('devBio', $userInfo['devBio'], $cookieExp, $cookiePath);  
            setcookie('phone', $userInfo['phone'], $cookieExp, $cookiePath);  
            // setcookie('type', 'developer', time() + (86400 * 30), "/");  
        }elseif($location == 'businesses'){
            setcookie('JWT', $userToken, $cookieExp, $cookiePath);  
            setcookie('busName', $userInfo['busName'], $cookieExp, $cookiePath);
            setcookie('busIndustry', $userInfo['busIndustry'], $cookieExp, $cookiePath);  
            setcookie('busBio', $userInfo['busBio'], $cookieExp, $cookiePath);  
            setcookie('firstName', $userInfo['firstName'], $cookieExp, $cookiePath);  
            setcookie('lastName', $userInfo['lastName'], $cookieExp, $cookiePath);    
            setcookie('password', $userInfo['password'], $cookieExp, $cookiePath);  
            setcookie('email', $userInfo['email'], $cookieExp, $cookiePath);  
            setcookie('phone', $userInfo['phone'], $cookieExp, $cookiePath);  
            // setcookie('type', 'business', time() + (86400 * 30), "/");  
        }
    }
?>


