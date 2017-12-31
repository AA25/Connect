<?php
    //Returns the project the developer is currently part of

    $pdo = get_db();

    //This API doesnt require an authorization header containing the JWT token
    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        //Getting the token sent
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        //Creating a token object from the token sent
        $verifiedJWT = new Jwt ($tokenInAuth);
        //Getting data out from the sent token object
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);
        //If the token passes verification then we know the data it contains is also valid and true
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'developer'){
            return retrieveCurrentProject($pdo, $userVerifiedData);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'Please log in to view this page');
    }

    function retrieveCurrentProject($pdo, $userVerifiedData){
        $currentProject = [];

        $project = $pdo->prepare("
            select projects.projectId, projects.projectName, projects.projectCategory, projects.projectBio, projects.projectBudget, projects.projectCountry, projects.projectCurrency, projects.projectStatus
            from ((projects inner join projectDevelopers on projects.projectId = projectDevelopers.projectId)
            inner join developers on projectDevelopers.devId = developers.devId)
            where developers.email = :devEmail;
        ");

        $project->execute([
            'devEmail' => $userVerifiedData['email']
        ]);

        if($project->rowCount() > 0){
            foreach($project as $current){
                $projectStatus = new ProjectStatusConverter($info['projectStatus']);

                array_push($currentProject,
                    $current['projectId'], $current['projectName'], $current['projectCategory'], $current['projectBio'], 
                    $current['projectCurrency'], $current['projectBudget'], $current['projectCountry'], $projectStatus->getStatus()
                );

                //array_push($currentProject, $tempArr);
            }
        }

        return Array('Success' => $currentProject);
    }
?>