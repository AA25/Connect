<?php 
    class Jwt {
       // private $userDetails;
        public $token;

        function __construct($token){
            //$this->userDetails = $userDetails;
            $this->token = $token;
        }
    
        function verifyJWT($sentJwt){
            $sentJwtParts = explode(".",$sentJWT);
        
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