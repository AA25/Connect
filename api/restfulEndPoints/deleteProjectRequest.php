<?php
    //Delete a project request you have made as the developer
    //Or delete a request incoming to your project as a business

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
        if($verifiedJWT->verifyJWT($verifiedJWT->token)){
            $deleteRequest = $this->args[0];
            return prepareDeleteRequest($pdo, $userVerifiedData, $deleteRequest);
        }else{
            return Array('Error' => 'Permission denied');
        }
    }else{
        return Array('Error' => 'Please log in to view this page');
    }

    function prepareDeleteRequest($pdo, $userVerifiedData, $deleteRequest){
        if($userVerifiedData['type'] == 'developer'){
            //Deletes a project request made by a specific developer (when dev clicks delete)
            $delete = $pdo->prepare("delete projectRequests from projectRequests 
                inner join developers on projectRequests.devId = developers.devId 
                where developers.email = :devEmail and projectReqId = :projectReqId
            ");

            $delete->execute([
                'devEmail'  => $userVerifiedData['email'],
                'projectReqId' => $deleteRequest
            ]);
            
            if($delete->rowCount() > 0){
                return Array('Success' => 'Request deleted');
            }else{
                return Array('Error' => 'Project to delete was not found');
            }

        }elseif($userVerifiedData['type'] == 'business'){
            return Array('TODO' => 'businesses wants to delete a request');
        }
    }
?>