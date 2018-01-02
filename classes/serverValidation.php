<?php 
    //Object that contains validation methods for each form on the site as well as sanitisation methods to sanitise data before inserting into the database
    //Testers within my tribe helped with validation and security
    //Got some basic ideas of php form validation from https://www.w3schools.com/php/php_form_validation.asp
    // And how to remove script tags from https://stackoverflow.com/questions/28255873/removing-script-tags-using-preg-replace

    //Main steps are to sanitise inputs then validate.

    class ServerValidation{

        //Remove scripts and convert html tags to prevent certain attacks
        public function sanitisation($formData){
            $formData = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $formData);
            //$formData = trim($formData);
            //$formData = stripslashes($formData);
            $formData = htmlspecialchars($formData);
            return $formData;
        }

        public function registerDeveloperSanitisation(&$firstName,&$lastName,&$dob,&$languages,&$email,&$password,&$devBio,&$phone,&$username){
            $firstName      = $this->sanitisation($firstName);
            $lastName       = $this->sanitisation($lastName);
            $dob            = $this->sanitisation($dob);
            $languages      = $this->sanitisation($languages);
            $email          = $this->sanitisation($email);
            $password       = $this->sanitisation($password);
            $devBio         = $this->sanitisation($devBio);
            $phone          = $this->sanitisation($phone);
            $username       = $this->sanitisation($username);

            return $this->registerDeveloperValidation($firstName,$lastName,$dob,$languages,$email,$password,$devBio,$phone,$username);
        }

        public function registerBusinessSanitisation(&$busName,&$busIndustry,&$busBio,&$firstName,&$lastName,&$password,&$email,&$phone,&$username){
            $busName        = $this->sanitisation($busName);
            $busIndustry    = $this->sanitisation($busIndustry);
            $busBio         = $this->sanitisation($busBio);
            $firstName      = $this->sanitisation($firstName);
            $lastName       = $this->sanitisation($lastName);
            $password       = $this->sanitisation($password);
            $email          = $this->sanitisation($email);
            $phone          = $this->sanitisation($phone);
            $username       = $this->sanitisation($username);

            return $this->registerBusinessValidation($busName,$busIndustry,$busBio,$firstName,$lastName,$password,$email,$phone,$username);
        }

        public function loginSanitisation(&$email,&$password,&$searchLocation){
            $email              = $this->sanitisation($email);
            $password           = $this->sanitisation($password);
            $searchLocation     = $this->sanitisation($searchLocation);

            return $this->loginValidation($email,$password,$searchLocation);
        }

        public function registerProjectSanitisation(&$projectCategory,&$projectBio,&$projectBudget,&$projectDeadline,&$projectCountry,&$projectLanguage
        ,&$projectCurrency,&$projectName){

            $projectCategory    = $this->sanitisation($projectCategory);
            $projectBio         = $this->sanitisation($projectBio);
            $projectBudget      = $this->sanitisation($projectBudget);
            $projectDeadline    = $projectDeadline;
            $projectCountry     = $this->sanitisation($projectCountry);
            $projectLanguage    = $this->sanitisation($projectLanguage);
            $projectCurrency    = $this->sanitisation($projectCurrency);
            $projectName        = $this->sanitisation($projectName);

            return $this->registerProjectValidation($projectCategory,$projectBio,$projectBudget,$projectDeadline,$projectCountry,$projectLanguage
            ,$projectCurrency,$projectName);
        }

        public function sendRequestSanitisation(&$projectId,&$devMsg){
            $projectId  = $this->sanitisation($projectId);
            $devMsg     = $this->sanitisation($devMsg);

            return $this->sendRequestValidation($projectId,$devMsg);
        }

        public function sendProjectMessageSanitisation(&$sentMessage){
            $sentMessage = $this->sanitisation($sentMessage);

            return $this->sendProjectMessageValidation($sentMessage);
        }

        public function sendProjectMessageValidation($sentMessage){
            if( (strlen($sentMessage) > 500) || empty($sentMessage) ){
                //return false;
                return "Message cannot be longer than 500 characters or empty";
            }

            return true;
        }

        public function sendRequestValidation($projectId,$devMsg){
            if( (gettype($projectId) == integer) || empty($projectId) ){
                //return false;
                return "Project id has to be a number and not empty";
            }
            if( (strlen($devMsg) > 500) || empty($devMsg) ){
                //return false;
                return "Project category cannot be longer than 500 characters or empty";
            }

            return true;
        }

        public function registerProjectValidation($projectCategory,$projectBio,$projectBudget,$projectDeadline,$projectCountry,$projectLanguage
        ,$projectCurrency,$projectName){

            if( (strlen($projectCategory) > 56) || empty($projectCategory)){
                //return false;
                return "Project category cannot be longer than 56 characters or empty";
            }
            if( (strlen($projectBio) > 500) || empty($projectBio) ){
                //return false;
                return "Project bio cannot be longer than 500 characters or empty";
            }
            if( (strlen($projectBudget) > 56) || empty($projectBudget)){
                //return false;
                return "Project budget cannot be longer than 56 characters or empty";
            }
            // if(empty($projectDeadline)){
            //     return false;
            // }
            if( (strlen($projectCountry) > 56) || empty($projectCountry)){
                //return false;
                return "Project country cannot be longer than 56 characters or empty";
            }
            if( (strlen($projectLanguage) > 100) || empty($projectLanguage)){
                //return false;
                return "Project language cannot be longer than 56 characters or empty";
            }
            if( (strlen($projectCurrency) > 56) || empty($projectCurrency)){
                //return false;
                return "Project currency cannot be longer than 56 characters or empty";
            }
            // if(empty($dateEntered)){
            //     return false;
            // }
            // if(empty($startDate)){
            //     return false;
            // }
            // if(empty($projectStatus)){
            //     return false;
            // }
            if( (strlen($projectName) > 56) || empty($projectName)){
                //return false;
                return "Project name cannot be longer than 56 characters or empty";
            }
            return true;
        }

        public function loginValidation($email,$password,$searchLocation){

            if( !filter_var($email, FILTER_VALIDATE_EMAIL) || (strlen($email) > 56) || empty($email)){
                //return false;
                return "Email must be valid, not empty and no longer than 56 characters";
            }
            if( (strlen($password) > 500) || empty($password)){
                //return false;
                return "Password cannot be longer than 500 or empty";
            }
            if( (strlen($searchLocation) > 20) || empty($searchLocation)){
                //return false;
                return "Location cannot be longer than 20 or empty";
            }

            return true;
        }

        public function registerBusinessValidation($busName,$busIndustry,$busBio,$firstName,$lastName,$password,$email,$phone,$username){

            if( (strlen($busName) > 56) || empty($busName)){
                //return false;
                return "Company name cannot be longer than 56 characters or empty";
            }
            if( (strlen($busIndustry) > 56) || empty($busIndustry) ){
                //return false;
                return "Industry cannot be longer than 56 characters or empty";
            }
            if( (strlen($busBio) > 500) || empty($busBio)){
                //return false;
                return "Bio cannot be longer than 500 characters or empty";
            }
            if( !(ctype_alpha($firstName)) || (strlen($firstName) > 56) || empty($firstName) ){
                //return false;
                return "First name cannot be longer than 56 characters or empty and only contain letters";
            }
            if( !(ctype_alpha($lastName)) || (strlen($lastName) > 56) || empty($lastName)){
                //return false;
                return "Last name cannot be longer than 56 characters or empty and only contain letters";
            }
            if( (strlen($password) > 500) || empty($password) ){
                //return false;
                return "Password cannot be longer than 500 or empty";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email) || (strlen($email) > 56) ) {
                //return false;
                return "Email must be valid, not empty and no longer than 56 characters";
            }
            if( (strlen($phone) > 45) || empty($phone) ){
                //return false;
                return "Phone cannot be longer than 45 characters or empty";
            }
            if( (strlen($username) > 100) || empty($username) ){
                //return false;
                return "Username cannot be longer than 100 characters or empty";
            }

            return true;
        } 

        public function registerDeveloperValidation($firstName,$lastName,$dob,$languages,$email,$password,$devBio,$phone,$username){

            if( !(ctype_alpha($firstName)) || (strlen($firstName) >56) || empty($firstName) ){
                //return false;
                return "First name cannot be longer than 56 characters or empty and only contain letters";
            }
            if( !(ctype_alpha($lastName)) || (strlen($lastName) >56) || empty($lastName)){
                //return false;
                return "Last name cannot be longer than 56 characters or empty and only contain letters";
            }

            //Ensure the user is at least 16
            $userDob = new DateTime($dob);
            $current = new DateTime(date("Y-m-d"));
            $userAge = $current->diff($userDob)->y;
            if( strtotime($dob) == '' || empty($dob) || $userAge < 16){
                //return false;
                return "DoB must be a date, not empty and user must be older than 16";
            }
            if( (strlen($languages) > 500) || empty($languages) ){
                //return false;
                return "Languages cannot be longer than 500 characters or empty";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email) || (strlen($email) > 56) ) {
                //return false;
                return "Email must be valid, not empty and no longer than 56 characters";
            }
            if( (strlen($password) > 500) || empty($password) ){
                //return false;
                return "Password cannot be longer than 500 or empty";
            }
            if( (strlen($devBio) > 500) || empty($devBio) ){
                //return false;
                return "Bio cannot be longer than 500 characters or empty";
            }
            if( (strlen($phone) > 45) || empty($phone) ){
                //return false;
                return "Phone cannot be longer than 45 characters or empty";
            }
            if( (strlen($username) > 100) || empty($username) ){
                //return false;
                return "Username cannot be longer than 100 characters or empty";
            }

            return true;
        } 
    }
?>