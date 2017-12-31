<?php
    //Post a new business project

    $pdo = get_db();
    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        //Getting the token sent
        $tokenInAuth = str_replace("Bearer ", "", $headers['Authorization']);
        //Creating a token object from the token sent
        $verifiedJWT = new Jwt ($tokenInAuth);
        //Getting data out from the sent token object
        $userVerifiedData = $verifiedJWT->getDataFromJWT($verifiedJWT->token);
        //If the token passes verification then we know the data it contains is also valid and true

        if($verifiedJWT->verifyJWT($verifiedJWT->token) && $userVerifiedData['type'] == 'business'){
            //Assign the body of the post request to this var
            $projectJSON = json_decode($this->file,true);
            return prepareInsertProject($pdo, $userVerifiedData, $projectJSON);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'Please log in to view this page');
    }

    function prepareInsertProject($pdo, $userVerifiedData,$projectJSON){
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

                return insertProject($projectJSON, $pdo, $busId,$userVerifiedData);

            }else{
                return Array("Error" => "Validation Error");
            }
        }else{
            //Error with retriving the business id for particular account
            return Array("Error" => "Error with account");
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
            $pdo->commit();
            return Array('Success' => 'Successful registration of project');
        }catch(Exception $e){
            $pdo->rollBack();
            return Array('Error' => 'Error with registration of project');
        }
    }

?>