<?php 
require "../../includes/init.inc.php";
$pdo = get_db();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$registerJSON = json_decode(file_get_contents('php://input'),true);

$validationCheck = new ServerValidation();

if($validationCheck->registerBusinessSanitisation(
    $registerJSON['busName'],$registerJSON['busIndustry'],$registerJSON['busBio'],$registerJSON['firstName'],
    $registerJSON['lastName'],$registerJSON['password'],$registerJSON['email'],
    $registerJSON['phone'],$registerJSON['username'])){

    insertBusiness($pdo,$registerJSON);

}else{
    echo json_encode(array('Error' => 'Validation failed'));
}

function insertBusiness($pdo,$registerJSON){
    //http://thisinterestsme.com/php-pdo-transaction-example/
    //We start our transaction.
    $pdo->beginTransaction();
    try{
        $r = $pdo->prepare(
            "insert into
            businesses (busName, busIndustry, busBio, firstName, lastName, password, email, phone, type, username)
            values(:busName, :busIndustry, :busBio, :firstName, :lastName, :password, :email, :phone, :type, :username);"
        );
        $r->execute([
            'busName' => $registerJSON['busName'],
            'busIndustry' => $registerJSON['busIndustry'],
            'busBio' => $registerJSON['busBio'],
            'firstName' => $registerJSON['firstName'],
            'lastName' => $registerJSON['lastName'],
            'password' => $registerJSON['password'],
            'email' => $registerJSON['email'],
            'phone' => $registerJSON['phone'],
            'type' => 'business',
            'username' => $registerJSON['username']
        ]);
    
        //We've got this far without an exception, so commit the changes.
        $pdo->commit();        
        echo json_encode(array('Success' => 'Successful registration'));
        
    }
    catch(Exception $e){
        //An exception has occured, which means that the registration failed from the about try
        //echo $e->getMessage();
        //Rollback the transaction.
        $pdo->rollBack();
        echo json_encode(array('Error' => $e));

        //echo json_encode(array('Error' => 'Registration failed'));
    }
}