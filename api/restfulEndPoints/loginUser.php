<?php
    //Log user in and provide token if login is successfully

    $pdo = get_db();
    
    $loginDetails = json_decode($this->file,true);

    //Santise and validate the login details in post request
    $validationCheck = new ServerValidation();

    if ($validationCheck->loginSanitisation(
        $loginDetails['email'],$loginDetails['password'],$loginDetails['location']
    )){
        //Attempt to retrieve user details from provided login details and then prepares sql query
        if($loginDetails['location'] == 'developers'){
            $r = prepDevSQL($pdo);
        }elseif($loginDetails['location'] == 'businesses'){
            $r = prepBusSQL($pdo);
        }else{
            //Where location isnt developers or businesses
            return Array('Error' => 'account type not found');
            break;
        }

        //Hash the entered password to see if it matches the hashed password in the db
        //$password = password_hash($loginDetails['password'], PASSWORD_DEFAULT);
        $r->execute([
            'email' => $loginDetails['email'],
        ]);


        //If num of rows returned is greater than 0 we know the account exists
        if($r->rowCount() > 0){
            $userInfo = [];
            $userInfo['Success'] = 'Successful login';
            foreach($r as $info){
                //But first we check if the password given matches the hashed password for the account
                if(password_verify($loginDetails['password'], $info['password'])){
                    //Then continue to push the project details to the userInfo array 
                    if($loginDetails['location'] == 'developers'){
                        pushDevDetails($userInfo, $info);
                    }elseif($loginDetails['location'] == 'businesses'){
                        pushBusDetails($userInfo, $info);
                    }
                }else{
                    return Array('Error' => 'Incorrect login details'); 
                }
            }

            //Create a new token object that will be the token we return to the user
            $userToken = new Jwt('');
            //The token will contain some of the basic user details
            $userToken->setToken($userInfo);
            //Then return the token string that needs to be attached to future requests
            $value = $userToken->getToken();
            return Array('Success' => $userToken->getToken());
        }else{
            //Account does not exist
            return Array('Error' => 'Incorrect login details');
        }
    }else{
        return Array('Error' => 'Validation Failure');
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
?>