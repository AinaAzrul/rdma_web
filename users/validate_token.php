<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/rdma_web/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required to decode jwt
include_once '../config/core.php';
require '../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\KEY;

// retrieve gieve jwt here
// get posted data
//$data = json_decode(file_get_contents("php://input"));
 
// get jwt
//$jwt=isset($data->jwt) ? $data->jwt : "";
$jwt= substr(getallheaders()["Authorization"], 7);

// if jwt is not empty
if($jwt){

    // if decode succeed, show user details
    try {
        // decode jwt
        $decoded = JWT::decode($jwt, new key($key,'HS256'));
        
        // set response code
        http_response_code(200);
 
        // show user details
        echo json_encode(array(
            "message" => "Access granted.",
            "data" => $decoded->data
        ));
    }
 
    // if decode fails, it means jwt is invalid
catch (Exception $e){
 
    // set response code
    http_response_code(401);
 
    // tell the user access denied  & show error message
    echo json_encode(array(
        "message" => "Access denied.",
        "error" => $e->getMessage()
    ));
}

}
 
// show error message if jwt is empty
else{
 
    // set response code
    http_response_code(401);
 
    // tell the user access denied
    echo json_encode(array("message" => "Access denied."));
    return false;
}

?>