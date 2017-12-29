<?php
    //Registers a new developer

    $pdo = get_db();

    //This is the data that was inside the body of the request sent
    $registerJSON = json_decode($this->file,true);

    $validationCheck = new ServerValidation();

    //If the serside validation passes then proceed with the inseration of the project
    if($validationCheck->registerDeveloperSanitisation(
        $registerJSON['firstName'],$registerJSON['lastName'],$registerJSON['dob'],
        $registerJSON['languages'],$registerJSON['email'],$registerJSON['password'],$registerJSON['devBio'],
        $registerJSON['phone'],$registerJSON['username'])){

            return insertDeveloper($pdo,$registerJSON);

    }else{
        return Array('Error' => 'Validation failed');
    }

    function insertDeveloper($pdo,$registerJSON){
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
?>