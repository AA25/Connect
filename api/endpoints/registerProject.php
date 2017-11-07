<?php 

    require "../../includes/init.inc.php";
    $pdo = get_db();
    // header("Access-Control-Allow-Origin: *");
    // header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    //echo json_encode(array('Success' => 'Received')); 
    
    //This page should only be accessible if JWT is verified and you're a business
    $verifiedJWT = 'eyJhbGciOiAiSFMyNTYiLCJ0eXAiOiAiSldUIn0=.eyJTdWNjZXNzIjoiU3VjY2Vzc2Z1bCBsb2dpbiIsImZpcnN0TmFtZSI6InRlc3QiLCJsYXN0TmFtZSI6InRlc3QiLCJkb2IiOiIxOTk0LTA2LTI1IiwibGFuZ3VhZ2VzIjoiZW5nbGlzaCIsImVtYWlsIjoidGVzdEB0ZXN0LmNvbSIsImRldkJpbyI6ImJpbyIsInBob25lIjoiMSIsInR5cGUiOiJkZXZlbG9wZXIifQ==.jUOCuAkQzTvVCX9Fx1PJ8MTnH9XhZAYB/HjCQGj1Rg4=';

    $userVerifiedData = getDataFromJWT($verifiedJWT);    
    var_dump($userVerifiedData);

    $pdo->beginTransaction();
    try{
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
        }
        //Now we insert a new project with the busId 
        // $result = $pdo->prepare(
        //     "insert into
        //     projects (businessId, projectCategory, projectBio, projectBudget, projectDeadline, projectCountry, projectLanguage, projectCurrency, dateEntered, startDate)
        //     values(:businessId, :projectCategory, :projectBio, :projectBudget, :projectDeadline, :projectCountry, :projectLanguage, :projectCurrency, :dateEntered, :startDate);"
        // );
        // $result->execute([
        //     'businessId'
        // ]);
    }catch(Exception $e){
        echo 'error';
    }
?>