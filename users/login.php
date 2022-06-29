<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//both of these include_once will be ok if deleted 
// include_once '../config/core.php';

include_once 'user.php';
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate user object
$user = new User($db);

// check email existence here
// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->email = $data->email;
$email_exists = $user->emailExists();
 
// generate jwt will be here
// check if email exists and if password is correct
if($email_exists && password_verify($data->password, $user->password)){
 
    $token = array(
       "iat" => $issued_at,
       "exp" => $expiration_time,
       "iss" => $issuer,
       "data" => array(
           "id" => $user->id,
           "role" => $user->role
       )
    );
 
    // set response code
    http_response_code(200);
   
    // generate jwt
    $jwt = JWT::encode($token, $key, 'HS256');
    echo json_encode(
            array(
                "status" => http_response_code(200),
                "message" => "Successful login.",
                "jwt" => $jwt
            )
        );
 
}
 
// login failed will be here
// login failed
else{
 
    // set response code
    http_response_code(401);
 
    // tell the user login failed
    echo json_encode(array("message" => "Login failed."));
}

?>