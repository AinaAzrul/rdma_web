<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object file
/*include_once '../config/database.php';
include_once 'user.php';*/
  
// required to encode json web token
//include_once '../config/core.php';
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\KEY;

// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare user object
$user = new User($db);
 
// get user id
$data = json_decode(file_get_contents("php://input"));
  
// set user id to be deleted
$user->firstname = $data->firstname;
  
// delete the user
if($user->delete()==true){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "user item was deleted."));
}
  
// if unable to delete the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete user."));
}

?>