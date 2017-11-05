<?php 
require "../../includes/init.inc.php";
$pdo = get_db();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$registerJSON = json_decode(file_get_contents('php://input'),true);

if(!empty($registerJSON['firstName']) && !empty($registerJSON['lastName'])){
    //http://thisinterestsme.com/php-pdo-transaction-example/
    //We start our transaction.
    $pdo->beginTransaction();
    try{
        $r = $pdo->prepare(
            "insert into
            developers (firstName, lastName, dob, languages, email, password, devBio, phone, type)
            values(:firstName, :lastName, :dob, :languages, :email, :password, :devBio, :phone, :type);"
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
            'type' => 'developer'
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
    echo json_encode(array('Error' => 'Empty Fields'));
}
?>