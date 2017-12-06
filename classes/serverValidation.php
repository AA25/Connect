<?php 
    //Object that contains validation methods for each form on the site as well as sanitisation methods to sanitise data before inserting into the database
    //Testers within my tribe helped with validation and security
    //Got some basic ideas of php form validation from https://www.w3schools.com/php/php_form_validation.asp
    class ServerValidation{

        public function sanitisation($formData){
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

        public function registerBusinessValidation($busName,$busIndustry,$busBio,$firstName,$lastName,$password,$email,$phone,$username){

            if( (strlen($busName) > 56) || empty($busName)){
                return false;
            }
            if( (strlen($busIndustry) > 56) || empty($busIndustry) ){
                return false;
            }
            if( (strlen($busBio) > 500) || empty($busBio)){
                return false;
            }
            if( !(ctype_alpha($firstName)) || (strlen($firstName) > 56) || empty($firstName) ){
                return false;
            }
            if( !(ctype_alpha($lastName)) || (strlen($firstName) > 56) || empty($firstName)){
                return false;
            }
            if( (strlen($password) > 500) || empty($password) ){
                return false;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email) ) {
                return false;
            }
            if( (strlen($phone) > 45) || empty($phone) ){
                return false;
            }
            if( (strlen($username) > 100) || empty($username) ){
                return false;
            }

            return true;
        } 

        public function registerDeveloperValidation($firstName,$lastName,$dob,$languages,$email,$password,$devBio,$phone,$username){

            if( !(ctype_alpha($firstName)) || (strlen($firstName) >56) || empty($firstName) ){
                return false;
            }
            if( !(ctype_alpha($lastName)) || (strlen($firstName) >56) || empty($firstName)){
                return false;
            }
            if( strtotime($dob) == '' || empty($dob) ){
                return false;
            }
            if( (strlen($languages) > 500) || empty($languages) ){
                return false;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email) ) {
                return false;
            }
            if( (strlen($password) > 500) || empty($password) ){
                return false;
            }
            if( (strlen($devBio) > 500) || empty($devBio) ){
                return false;
            }
            if( (strlen($phone) > 45) || empty($phone) ){
                return false;
            }
            if( (strlen($username) > 100) || empty($username) ){
                return false;
            }

            return true;
        } 
    }
?>