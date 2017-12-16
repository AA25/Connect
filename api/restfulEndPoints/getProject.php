<? 
    //Retrieves the project details of a certain project

    $pdo = get_db();
    $reqProjectId = (int)$args[0];
    $returnProject = ['Success' => []];
    $returnProject['Success']['userType'] = 'guest';

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
            $returnProject['Success']['userType'] = $userVerifiedData['type'];
        }
    }

    $result = $pdo->prepare("select projectCategory, projectBio, projectBudget, projectDeadline, projectCountry, projectLanguage, projectCurrency, dateEntered, startDate, projectStatus, projectName from projects where projectId = :projectId");

    $result->execute([
        'projectId' => $reqProjectId
    ]);

    if($result->rowCount() > 0){
        foreach($result as $row){
            pushProjectDetails($returnProject['Success'], $row);
        }
        return $returnProject;
    }else{
        return Array('Error' => "Oops, the project you're looking doesn't seem to exist");
    }

    function pushProjectDetails(&$returnProject, $info){
        $projectStatus = new ProjectStatusConverter($info['projectStatus']);
        array_push($returnProject, 
            Array(
                'projectCategory'   => $info['projectCategory'],
                'projectBio'        => $info['projectBio'],
                'projectCurrency'   => $info['projectCurrency'],
                'projectBudget'     => $info['projectBudget'],
                'projectCountry'    => $info['projectCountry'],
                'projectDeadline'   => $info['projectDeadline'],
                'projectLanguage'   => $info['projectLanguage'],
                'dateEntered'       => $info['dateEntered'],
                'startDate'         => $info['startDate'],
                'projectStatusCode' => $info['projectStatus'],
                'projectStatus'     => $projectStatus->getStatus(),
                'projectName'       => $info['projectName'],
            )
        );
    }

?>