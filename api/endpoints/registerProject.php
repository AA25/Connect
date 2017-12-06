<?php 
    require "../../includes/init.inc.php";
    $pdo = get_db();

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        $verifiedJWT = new Jwt ($tokenInAuth);
        //echo json_encode(Array('Cookie' => $verifiedJWT));
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);  
        //echo json_encode(Array('Server' => verifyJWT($verifiedJWT)));
        //This page should only be accessible if JWT is verified and you're a business
        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
            prepareInsertProject($pdo, $userVerifiedData);
            //echo json_encode(Array('Error' => 'Permission access'));
        }else{
            echo json_encode(Array('Error' => 'Permission denied'));
        }
    }else{
        echo json_encode(Array('Error' => 'No Authorization Header'));
    }

    function prepareInsertProject($pdo, $userVerifiedData){
        $projectJSON = json_decode(file_get_contents('php://input'),true);

        $busId = null;
        //Retrieve businessId from the business trying to add a new project
        $result = $pdo->prepare("select busId from businesses where email = :email");
        $result->execute([
            'email' => $userVerifiedData["email"]
        ]);
        //If num of rows returned is greater than 0 we know we have a result
        if($result->rowCount() > 0){
            foreach($result as $id){
                $busId = $id['busId'];
            }
            //Validate data before inserting into the database
            $validation = new ServerValidation();

            if($validation->registerProjectSanitisation($projectJSON['projectCategory'],$projectJSON['projectBio'],$projectJSON['projectBudget'],
            $projectJSON['projectDeadline'],$projectJSON['projectCountry'],$projectJSON['projectLanguage'],$projectJSON['projectCurrency'],
            $projectJSON['projectName'])){

                insertProject($projectJSON, $pdo, $busId,$userVerifiedData);

            }else{
                echo json_encode(Array("Error" => "Validation Error"));
            }
        }else{
            //Error with retriving the business id for particular account
            echo json_encode(Array("Error" => "Error with account"));
        }
    }
    

    function insertProject($projectJSON, $pdo, $busId, $userVerifiedData){
        $pdo->beginTransaction();
        try{
            //Now we insert a new project with the busId 
            $result = $pdo->prepare(
                "insert into
                projects (businessId, projectCategory, projectBio, projectBudget, projectDeadline, projectCountry, projectLanguage, projectCurrency, dateEntered, startDate, projectStatus, projectName)
                values(:businessId, :projectCategory, :projectBio, :projectBudget, :projectDeadline, :projectCountry, :projectLanguage, :projectCurrency, :dateEntered, :startDate, :projectStatus, :projectName);"
            );
            $result->execute([
                'businessId'        => $busId,
                'projectCategory'   => $projectJSON['projectCategory'],
                'projectBio'        => $projectJSON['projectBio'],
                'projectBudget'     => $projectJSON['projectBudget'],
                'projectDeadline'   => NULL,
                'projectCountry'    => $projectJSON['projectCountry'],
                'projectLanguage'   => $projectJSON['projectLanguage'],
                'projectCurrency'   => $projectJSON['projectCurrency'],
                'dateEntered'       => date("Y-m-d H:i:s"),
                'startDate'         => date("Y-m-d"),
                'projectStatus'     => 0,
                'projectName'       => $projectJSON['projectName']
            ]);

            //We've got this far without an exception, so commit the changes.
            echo json_encode(array('Success' => 'Successful registration of project'));
            $pdo->commit();
        }catch(Exception $e){
            echo json_encode(Array('Error' => 'Error with registration of project'));
            $pdo->rollBack();
        }
    }
    
?>