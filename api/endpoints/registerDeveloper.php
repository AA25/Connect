<?php 
require "../../includes/init.inc.php";
$pdo = get_db();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//This is the data that was inside the body of the request sent
$registerJSON = json_decode(file_get_contents('php://input'),true);

$validationCheck = new ServerValidation();

$validationCheck->registerDeveloperSanitisation($registerJSON['firstName'],$registerJSON['lastName'],$registerJSON['dob'],
$registerJSON['languages'],$registerJSON['email'],$registerJSON['password'],$registerJSON['devBio'],
$registerJSON['phone'],$registerJSON['username']);



//If the serside validation passes then proceed with the inseration of the project
if($validationCheck->registerDeveloperSanitisation(
    $registerJSON['firstName'],$registerJSON['lastName'],$registerJSON['dob'],
    $registerJSON['languages'],$registerJSON['email'],$registerJSON['password'],$registerJSON['devBio'],
    $registerJSON['phone'],$registerJSON['username'])){
//if(!empty($registerJSON['firstName']) && !empty($registerJSON['lastName'])){
    // Learned how to use transactions from http://thisinterestsme.com/php-pdo-transaction-example/
    //We start our transaction.
    $pdo->beginTransaction();
    try{
        //Insert user data into the database
        $r = $pdo->prepare(
            "insert into
            developers (firstName, lastName, dob, languages, email, password, devBio, phone, type, username)
            values(:firstName, :lastName, :dob, :languages, :email, :password, :devBio, :phone, :type, :username);"
          );

        $r->execute([
            'firstName' => $registerJSON['firstName'],
            'lastName' => $registerJSON['lastName'],
            'dob' => $registerJSON['dob'],
            'languages' => $registerJSON['languages'],
            'email' => $registerJSON['email'],
            'password' => $registerJSON['password'],
            'devBio' => $registerJSON['devBio'],
            'phone' => $registerJSON['phone'],
            'type' => 'developer',
            'username' => $registerJSON['username']            
        ]);
    
        //We've got this far without an exception, so commit the changes.
        echo json_encode(array('Success' => 'Successful registration'));
        $pdo->commit();
        
    }
    catch(Exception $e){
        //An exception has occured, which means that the registration failed from the about try
        //echo $e->getMessage();
        //Rollback the transaction.
        echo json_encode(array('Error' => 'Registration failed'));
        $pdo->rollBack();
    }
}else{
    echo json_encode(array('Error' => 'Validation failed'));
}
?>