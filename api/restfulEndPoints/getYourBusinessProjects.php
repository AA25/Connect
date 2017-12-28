<?php
    //Retrieves all projects owned by a business
    $pdo = get_db();
    //This API doesnt require an authorization header containing the JWT token
    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        $verifiedJWT = new Jwt ($tokenInAuth);
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);  
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
            return retrieveProjects($pdo, $userVerifiedData);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'Please log in to view this page');
    }

    function retrieveProjects($pdo, $userVerifiedData){
        $returnProjects = [];
        $result = $pdo->prepare("
            select projects.projectId, projects.projectName, projects.projectCategory, projects.projectBio, projects.projectBudget, projects.projectCountry, projects.projectCurrency, projects.projectStatus from projects
            inner join businesses on projects.businessId = businesses.busId 
            where businesses.email = :busEmail order by dateEntered desc
        ");

        $result->execute([
            'busEmail' => $userVerifiedData['email']
        ]);

        if($result->rowCount() > 0){
            foreach($result as $info){
                $projectStatus = new ProjectStatusConverter($info['projectStatus']);
                $tempArr =  [
                    $info['projectId'], $info['projectName'], $info['projectCategory'], $info['projectBio'], 
                    $info['projectCurrency'], $info['projectBudget'], $info['projectCountry'], $projectStatus->getStatus(),
                    $info['projectStatus']
                ];
                array_push($returnProjects, $tempArr);
            }
            return Array('Success' => $returnProjects);
        }else{
            return Array('Error' => 'No results available');
        }
    }
?>