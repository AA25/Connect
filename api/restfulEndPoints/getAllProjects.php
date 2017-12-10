<?php
    $pdo = get_db();

    $returnProjects = ['userType' => 'guest','Projects' => []];
    $returnFrom = (int)$this->args[0];
    $returnAmount = (int)$this->args[1];
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
            $returnProjects['userType'] = $userVerifiedData['type'];
        }
    }


    return prepareProjects($pdo, $returnProjects, $returnFrom, $returnAmount);
    
    function prepareProjects($pdo, $returnProjects, $returnFrom, $returnAmount){
        $result = $pdo->prepare("
            select projectId, projectName, projectCategory, projectBio, projectBudget, projectCountry, projectCurrency, projectStatus 
            from projects where (projectStatus = 0 or projectStatus = 1) order by dateEntered desc limit :returnFrom, :returnAmount
        ");

        $result->execute([
            'returnFrom' => $returnFrom,
            'returnAmount' => $returnAmount
        ]);

        if($result->rowCount() > 0){
            foreach($result as $info){
                pushProjectDetails($returnProjects['Projects'], $info);
            }
            return Array('Success' => $returnProjects); 
        }else{
            return Array('Error' => 'No results available');
        }
    }

    function pushProjectDetails(&$returnProjects, $info){
        $projectStatus = new ProjectStatusConverter($info['projectStatus']);
        array_push($returnProjects, 
            Array(
                'projectId'         => $info['projectId'],
                'projectName'       => $info['projectName'],
                'projectCategory'   => $info['projectCategory'],
                'projectBio'        => $info['projectBio'],
                'projectBudget'     => $info['projectBudget'],
                'projectCountry'    => $info['projectCountry'],
                'projectCurrency'   => $info['projectCurrency'],
                'projectStatus'     => $projectStatus->getStatus()
            )
        );
    }
?>