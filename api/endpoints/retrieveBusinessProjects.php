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
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
            retrieveProjects($pdo, $userVerifiedData);
        }else{
            echo json_encode(Array('Error' => 'Permission denied'));
        }
    }else{
        echo json_encode(Array('Error' => 'Please log in to view this page'));
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
                $tempArr = [$info['projectId'], $info['projectName'], $info['projectCategory'], $info['projectBio'], 
                $info['projectCurrency'], $info['projectBudget'], $info['projectCountry'], $info['projectStatus']];
                array_push($returnProjects, $tempArr);
            }
            echo json_encode(Array('Success' => $returnProjects));
        }else{
            echo json_encode(Array('Error' => 'No results available'));
        }
    }
?>