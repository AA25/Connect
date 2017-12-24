<?php //Return all the developers working on each project owned by a business

$pdo = get_db();

$headers = apache_request_headers();
if(isset($headers['Authorization'])){
    $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
    $verifiedJWT = new Jwt ($tokenInAuth);
    $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);  
    //This API endpoint should only be accessible if JWT token  is verified and user is a developer
    if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
        $statusCondition = $this->args[0];
        return retrieveProjectIds($pdo, $userVerifiedData, $statusCondition);
    }else{
        return Array('Error' => 'Permission denied');
    }
}else{
    return Array('Error' => 'Please log in to view this page');
}

function retrieveProjectIds($pdo, $userVerifiedData, $statusCondition){
    $projectIds = [];
    if(isset($statusCondition)){
        // Only the project ids of projects in a particular status
        $selectProjectIds = $pdo->prepare("
            select projects.projectId, projects.ProjectName from projects 
            inner join businesses on projects.businessId = businesses.busId 
            where businesses.email = :businessEmail and projects.projectStatus = :projectStatus
        ");

        $selectProjectIds->execute([
            'businessEmail' => $userVerifiedData['email'],
            'projectStatus' => (int)$statusCondition
        ]);
    }else{
        // Returns the id of projects a business owns based on their business email
        $selectProjectIds = $pdo->prepare("
            select projects.projectId, projects.ProjectName from projects
            inner join businesses on projects.businessId = businesses.busId
            where businesses.email = :businessEmail
        ");

        $selectProjectIds->execute([
            'businessEmail' => $userVerifiedData['email']
        ]);
    }

    if($selectProjectIds->rowCount() <= 0){
        //Business has no projects 
        return Array('Error' => 'You currently have no projects set up or any projects in the status provided in the arguements');
    }else{
        foreach ($selectProjectIds as $projectId){
            //Creating an associative array where the key is the projectname and the value is its id
            array_push($projectIds, Array($projectId['ProjectName'] => $projectId['projectId']));
        }
        //echo json_encode(Array('Success' => $projectIds[0]['Prime']));
        return retrieveDevsPerProject($pdo, $userVerifiedData, $projectIds);
    }

}

function retrieveDevsPerProject($pdo, $userVerifiedData, $projectIds){
    //The projectId contains a key value pair, projectname and project id 

    $developerPerProjectId = [];
    $projectKeyValue = [];
    for($i = 0; $i < sizeof($projectIds); $i++){
        //Push the current project name into the array
        //Each project name will point to an array containing arrays of developers working under that project name
        $developerPerProjectId[key($projectIds[$i])] = [];
        array_push($projectKeyValue, Array(key($projectIds[$i]) => $projectIds[$i][key($projectIds[$i])]));
        //Query will return the current developers working on the current project
        $selectDev = $pdo->prepare("
            select developers.firstName, developers.lastName, developers.username 
            from ((developers inner join projectDevelopers on developers.devId = projectDevelopers.devId) 
            inner join projects on projectDevelopers.projectId = projects.projectId) 
            where projects.projectId = :currentProjectId;
        ");

        $selectDev->execute([
            'currentProjectId' => $projectIds[$i][key($projectIds[$i])]
        ]);

        //If true then there are developers currently assigned to this project
        if($selectDev->rowCount() > 0){
            foreach($selectDev as $developer){
                //Each developer will be respresented as an array
                //Push each developer array under the current project name array
                //$developerArr = [];
                array_push($developerPerProjectId[key($projectIds[$i])], 
                    //Developer Array
                    Array(
                        'name'      => $developer['firstName'].' '.$developer['lastName'],
                        'username'  => $developer['username']
                    )
                );

            }
        }

    }
    $response = [];
    //array_push($response, Array('ProjectDevelopers' => $developerPerProjectId));
    //return Array('Success' => $response);
    return Array('Success' => Array('ProjectDevelopers' => $developerPerProjectId), 'ProjectIds' => $projectKeyValue);
}

?>