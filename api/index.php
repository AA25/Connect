<?php
    require "../includes/init.inc.php";
// Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $API = new MyAPI($_SERVER['REQUEST_URI'], $_SERVER['HTTP_ORIGIN']);
    echo $API->processAPI();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}
    // header("Access-Control-Allow-Orgin: *");
    // header("Access-Control-Allow-Methods: *");
    // header("Content-Type: application/json");
    // //echo $_SERVER['SERVER_NAME'].'<br>';
    // $method = $_SERVER['REQUEST_METHOD'];

    // $uriParts = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
    // array_shift($uriParts);

    // $endpoint = array_shift($uriParts);

    // $verb = '';

    // $file = Null;

    // if (array_key_exists(0, $uriParts) && !is_numeric($uriParts[0])) {
    //     $verb = array_shift($uriParts);
    // }

    // switch($method) {
    //     case 'DELETE':
    //     case 'POST':
    //         //$this->request = $this->_cleanInputs($_POST);
    //         $this->file = file_get_contents("php://input");
    //         break;
    //     case 'GET':
    //         //$this->request = $this->_cleanInputs($_GET);
    //         break;
    //     case 'PUT':
    //         //$this->request = $this->_cleanInputs($_GET);
    //         $this->file = file_get_contents("php://input");
    //         break;
    //     default:
    //         //$this->_response('Invalid Method', 405);
    //         break;
    // }

    // $uri = $_SERVER['REQUEST_URI'];
    
    // $vars = explode('/', $uri);

    // var_dump($_SERVER['REQUEST_URI']);
    // var_dump($_REQUEST);
    // if( $vars[1] == 'project' && $vars[2] != ''){
    //     //include_once(__DIR__.'/views/projectDesc.php');
    // }
?>