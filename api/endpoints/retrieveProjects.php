<?php
require "../../includes/init.inc.php";
    $pdo = get_db();

    //important to tell your browser what we will be sending
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $returnProjects = ['userType' => 'guest','Projects' => []];

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

    $result = $pdo->prepare("
        select projectId, projectName, projectCategory, projectBio, projectBudget, projectCountry, projectCurrency, projectStatus 
        from projects where (projectStatus = 0 or projectStatus = 1) order by dateEntered desc limit :returnFrom, :returnAmount
    ");

    $result->execute([
        'returnFrom' => (int)$_GET['returnFrom'],
        'returnAmount' => (int)$_GET['returnAmount']
    ]);

    if($result->rowCount() > 0){
        
        foreach($result as $info){
            //array_push($returnProjects['Projects'], $info);
            pushProjectDetails($returnProjects['Projects'], $info);
        }
        echo json_encode($returnProjects);
    }else{
        echo json_encode(Array('Error' => 'No results available'));
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