<?php

    //Get information of a business

    $pdo = get_db();
    $username = $this->args[0];
    $returnProject = ['AccessType' => 'guest'];
    //This API doesnt require an authorization header containing the JWT token
    //However if it does additional  info of what type of account is trying to access the data will be return
    //This can be useful for the webpage to display the data a certain way depending on the account viewing it
    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        //Getting the token sent
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        //Creating a token object from the token sent
        $verifiedJWT = new Jwt ($tokenInAuth);
        //Getting data out from the sent token object
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);  
        //If the token passes verification then we know the data it contains is also valid and true
        if($verifiedJWT->verifyJWT($verifiedJWT->token)){
            //Setting the AccessType to be the type of the account accessing this data
            $returnProject['AccessType'] = $userVerifiedData['type'];
        }
    }
    return getBusinessInfo($pdo, $username, $returnProject);

    function getBusinessInfo($pdo, $username, $returnProject){
        $result = $pdo->prepare("select busName, busIndustry, busBio, firstName, lastName, email, phone, type, username from businesses where username = :username");

        $result->execute([
            'username' => $username
        ]);

        if($result->rowCount() > 0){
            foreach($result as $row){
                pushBusDetails($returnProject, $row);
            }
            return Array('Success' => $returnProject);
        }else{
            return Array('Error' => "Oops, the user you're looking doesn't seem to exist");
        }
    }

    function pushBusDetails(&$returnProject, $info){
        array_push($returnProject, 
            Array(
                'busName'       => $info['busName'],
                'busIndustry'   => $info['busIndustry'],
                'busBio'        => $info['busBio'],
                'firstName'     => $info['firstName'],
                'lastName'      => $info['lastName'],
                'email'         => $info['email'],
                'phone'         => $info['phone'],
                'type'          => $info['type'],
            )
        );
    }

?>