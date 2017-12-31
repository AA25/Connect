<?php
    //Get information of a developer

    $pdo = get_db();
    $username = $this->args[0];
    $username = str_replace("-",".",$username);
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
            //Setting the userType to be the type of the account accessing this data
            $returnProject['AccessType'] = $userVerifiedData['type'];
        }
    }

    return getDeveloperInfo($pdo, $username, $returnProject);

    function getDeveloperInfo($pdo, $username, $returnProject){
        //Query to return developer information
        $result = $pdo->prepare("select firstName, lastName, dob, languages, email, devBio, phone, type, currentProject from developers where username = :username");
        $result->execute([
            'username' => $username
        ]);
    
        if($result->rowCount() > 0){
            //Push developer info into an array that will returned at the end
            foreach($result as $row){
                pushDevDetails($returnProject, $row);
            }
            return Array('Success' => $returnProject);
        }else{
            return Array('Error' => "Oops, the user you're looking doesn't seem to exist");
        }
    }

    function pushDevDetails(&$returnProject, $info){
        array_push($returnProject, 
            Array(
                'firstName'         => $info['firstName'],
                'lastName'          => $info['lastName'],
                'dob'               => $info['dob'],
                'languages'         => $info['languages'],
                'email'             => $info['email'],
                'devBio'            => $info['devBio'],
                'phone'             => $info['phone'],
                'type'              => $info['type'],
                'currentProject'    => $info['currentProject']
            )
        );
    }

?>