<?php
    //Register a new business

    $pdo = get_db();

    //assign the body of the post request to this var
    $registerJSON = json_decode($this->file,true);

    //Create validation object that will sanitise and validate data posted in the request
    $validationCheck = new ServerValidation();

    $isValid = $validationCheck->registerBusinessSanitisation(
        $registerJSON['busName'],$registerJSON['busIndustry'],$registerJSON['busBio'],$registerJSON['firstName'],
        $registerJSON['lastName'],$registerJSON['password'],$registerJSON['email'],
        $registerJSON['phone'],$registerJSON['username']);

    if(gettype($isValid) == boolean && $isValid == true){

        //Once it passes validation then insert the new business
        return insertBusiness($pdo,$registerJSON);

    }else{
        //If isValid is not true it contains a validation error message
        return Array('Error' => $isValid);
    }

    function insertBusiness($pdo,$registerJSON){
        //We start our transaction.
        $pdo->beginTransaction();
        try{
            $r = $pdo->prepare(
                "insert into
                businesses (busName, busIndustry, busBio, firstName, lastName, password, email, phone, type, username)
                values(:busName, :busIndustry, :busBio, :firstName, :lastName, :password, :email, :phone, :type, :username);"
            );

            //Hash password before entering it into the db
            $password = password_hash($registerJSON['password'], PASSWORD_DEFAULT);
            $r->execute([
                'busName' => $registerJSON['busName'],
                'busIndustry' => $registerJSON['busIndustry'],
                'busBio' => $registerJSON['busBio'],
                'firstName' => $registerJSON['firstName'],
                'lastName' => $registerJSON['lastName'],
                'password' => $password,
                'email' => $registerJSON['email'],
                'phone' => $registerJSON['phone'],
                'type' => 'business',
                'username' => $registerJSON['username']
            ]);
        
            //We've got this far without an exception, so commit the changes.
            $pdo->commit();
            return Array('Success' => 'Successful registration');
            
        }
        catch(Exception $e){
            //An exception has occured, which means that the registration failed from the try
            //Rollback the transaction.
            $pdo->rollBack();
            return Array('Error' => 'Registration failed, please try another email or username');
        }
    }