<?php 
    // important to tell your browser what we will be sending
    // header('Content-type: application/json; charset=utf-8');
    
    require "../includes/init.inc.php";
    $pdo = get_db();

    if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['location'])){
        //Attempt to retrieve user details from provided login details and then prepares sql query
        if($_POST['location'] == 'developers'){
            $r = prepDevSQL($pdo);
        }elseif($_POST['location'] == 'businesses'){
            $r = prepBusSQL($pdo);
        }

        $r->execute([
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ]);

        //If num of rows returned is greater than 0 we know we have a result
        if($r->rowCount() > 0){
            $userInfo = [];
            $userInfo['Success'] = 'Successful login';
            foreach($r as $info){
                if($_POST['location'] == 'developers'){
                    pushDevDetails($userInfo, $info);
                }elseif($_POST['location'] == 'businesses'){
                    pushBusDetails($userInfo, $info);        
                }
            }
            $userToken = createJWT($userInfo);
            echo $userToken;
            //echo base64_encode(json_encode($userInfo));
        }else{
            echo base64_encode(json_encode(array('Error' => 'Incorrect login details')));
        }
    }else{
        echo base64_encode(json_encode(array('Error' => 'Empty Fields')));
    }

    function prepDevSQL(&$pdo){
        return $pdo->prepare(
            "select firstName, lastName, dob, languages, email, devBio, phone from ".$_POST['location']." where email = :email and password = :password"
        );  
    }

    function prepBusSQL(&$pdo){
        return $pdo->prepare(
            "select busName, busIndustry, busBio, firstName, lastName, email, phone from ".$_POST['location']." where email = :email and password = :password"
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
?>


