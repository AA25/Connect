<?php 

require "../../includes/init.inc.php";
$pdo = get_db();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$headers = apache_request_headers();
if(isset($headers['Authorization'])){
    $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
    $verifiedJWT = new Jwt ($tokenInAuth);
    $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);  
    //This API endpoint should only be accessible if JWT token  is verified and user is a developer
    if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
        $requestData = json_decode(file_get_contents('php://input'),true);        
        retrieveProjectIds($pdo, $userVerifiedData, $requestData);
    }else{
        echo json_encode(Array('Error' => 'Permission denied'));
    }
}else{
    echo json_encode(Array('Error' => 'No Authorization Header'));
}

function retrieveProjectIds($pdo, $userVerifiedData, $requestData){
    $projectIds = [];
    if(isset($_GET['statusCondition']) && isset($_GET['statusCondition']) == true){
        // Only the project ids of projects in a particular status
        $selectProjectIds = $pdo->prepare("
            select projects.projectId, projects.ProjectName from projects 
            inner join businesses on projects.businessId = businesses.busId 
            where businesses.email = :businessEmail and projects.projectStatus = :projectStatus
        ");

        $selectProjectIds->execute([
            'businessEmail' => $userVerifiedData['email'],
            'projectStatus' => $_GET['projectStatus']
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
        echo json_encode(Array('Error' => 'You currently have no projects set up'));
    }else{

        foreach ($selectProjectIds as $projectId){
            
            array_push($projectIds, Array($projectId['ProjectName'] => $projectId['projectId']));
        } 
        //echo json_encode(Array('Success' => $projectIds[0]['Prime']));
        retrieveDevsPerProject($pdo, $userVerifiedData, $projectIds);
    }

}

function retrieveDevsPerProject($pdo, $userVerifiedData, $projectIds){
    //The projectId contains a key value pair, projectname and project id 

    $developerPerProjectId = [];

    for($i = 0; $i < sizeof($projectIds); $i++){
        //Push the current project name into the array
        //Each project name will point to an array containing arrays of developers working under that project name
        
        $developerPerProjectId[key($projectIds[$i])] = [];
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
    echo json_encode(Array('Success' => $developerPerProjectId));
}

?>