<?php
    //https://scotch.io/tutorials/the-anatomy-of-a-json-web-token
    //This is a JWT class that will represent the jwt token we return to users when logging in
    class Jwt {
       // private $userDetails;
        public $token;

        //A string representing the jwt is used to construct the object
        function __construct($token){
            $sentJwt = str_replace(' ', '+', $token);
            $this->token = $token;
        }

        //The object has a verify method
        //whose arguement here is to the jwt string the user sent with their request
        function verifyJWT($sentJwt){
            //Basically we want to break down the sent user jwt into its 3 parts. Header, payload and signature
            // We then want to recreate the signature using the user jwt payload and header and hashing it 
            // with the secret key that only the server knows.
            //If the signature we created equals the signature of the user token then we know (if key wasnt comprimised)
            //That we created the token but also that the information the token holds is also true

            $sentJwt = str_replace(' ', '+', $sentJwt);
            $sentJwtParts = explode(".",$sentJwt);
            
            //We want to decode and encode it again incase values have been changed
            $sentJwtHeaderDec = base64_decode($sentJwtParts[0]);
            $sentJwtPayloadDec = base64_decode($sentJwtParts[1]);
            $sentJwtHeaderEnc = base64_encode($sentJwtHeaderDec);
            $sentJwtPayloadEnc = base64_encode($sentJwtPayloadDec);
            
            $sentJwtSig = $sentJwtParts[2];
        
            $serverHeaderPayload =  $sentJwtHeaderEnc . '.' . $sentJwtPayloadEnc;

            //If the signatures match, then that means the JWT sent is valid.
            $secretKey = 'secret';
            $serverSig = base64_encode(hash_hmac('sha256', $serverHeaderPayload, $secretKey, true));
            

            if($sentJwtSig == $serverSig){
                return true;
            }else{
                return false;
            }
        } 
    
        function getToken(){
            return $this->token;
        } 
    
        //Returns an array containing the information contained within the JWT when created
        function getDataFromJWT($verifiedJWT){
            $jwtParts = explode(".",$verifiedJWT);
            
            $jwtPayloadDec = base64_decode($jwtParts[1]);
        
            $userData = json_decode($jwtPayloadDec, true);
        
            return $userData;
        }
    
        //Creates a jwt based on the user details projects and sets it as the token
        function setToken($userInfo){
            $userInfoJSON = json_encode($userInfo);
            // JWT token structure is header.payload.signature
    
            // The header of the JWT token
            $headerEnc = base64_encode('{"alg": "HS256","typ": "JWT"}');
        
            // The payload of the JWT token
            $payloadEnc = base64_encode($userInfoJSON);
        
            // header and payload concat
            $headerPayload = $headerEnc . '.' . $payloadEnc;
        
            //Setting the secret key
            $secretKey = 'secret';
        
            // Creating the signature, a hash with the sha256 algorithm and the secret key
            $signature = base64_encode(hash_hmac('sha256', $headerPayload, $secretKey, true));
        
            // Creating the JWT token
            $jwtToken = $headerPayload . '.' . $signature;
    
            $this->token = (string)$jwtToken;
        }
    }
    
?>