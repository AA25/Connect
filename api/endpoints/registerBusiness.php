<?php 
require "../../includes/init.inc.php";
$pdo = get_db();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$registerJSON = json_decode(file_get_contents('php://input'),true);

if(!empty($registerJSON['busName']) && !empty($registerJSON['busIndustry'])){
    //http://thisinterestsme.com/php-pdo-transaction-example/
    //We start our transaction.
    $pdo->beginTransaction();
    try{
        $r = $pdo->prepare(
            "insert into
            businesses (busName, busIndustry, busBio, username, firstName, lastName, password, email, phone, type)
            values(:busName, :busIndustry, :busBio, :firstName, :lastName, :password, :email, :phone, :type);"
          );
        $r->execute([
            'busName' => $registerJSON['busName'],
            'busIndustry' => $registerJSON['busIndustry'],
            'busBio' => $registerJSON['busBio'],
            'username' => $registerJSON['username'],
            'firstName' => $registerJSON['firstName'],
            'lastName' => $registerJSON['lastName'],
            'password' => $registerJSON['password'],
            'email' => $registerJSON['email'],
            'phone' => $registerJSON['phone'],
            'type' => 'business'
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