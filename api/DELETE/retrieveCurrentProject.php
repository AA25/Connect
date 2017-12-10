<?php 
    require "../../includes/init.inc.php";
    $pdo = get_db();

    //important to tell your browser what we will be sending
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    //This API doesnt require an authorization header containing the JWT token
    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        $verifiedJWT = new Jwt ($tokenInAuth);
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);  
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'developer'){
            retrieveCurrentProject($pdo, $userVerifiedData);
        }else{
            echo json_encode(Array('Error' => 'Permission denied'));
        }
    }else{
        echo json_encode(Array('Error' => 'Please log in to view this page'));
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

        echo json_encode(Array('Success' => $currentProject));
    }
?>