<?php

// required to encode json web token
//include_once '../config/core.php';
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\KEY;
 
// files needed to connect to database
/*include_once '../config/database.php';*/
include_once 'user.php';
 
function  update_user(){

    // set your default time-zone
date_default_timezone_set('Asia/Kuala_Lumpur');

// variables used for jwt
$key = "33F06AED8BF74357226AB8EDD16F684FC12E2948C5F818BAB1B2C8E56518630D";
$issued_at = time();
$expiration_time = $issued_at + (60 * 60); // valid for 1 hour
$issuer = "http://localhost/rdma_web";

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate user object
$user = new User($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// get jwt
$jwt=isset($data->token) ? $data->token : "";
// $jwt = substr(getallheaders()["Authorization"], 7);

// decode jwt here
// if jwt is not empty
if($jwt){
 
    // if decode succeed, show user details
    try {
        
        // decode jwt
        $decoded = JWT::decode($jwt, new Key($key,'HS256'));
        $userData= $data->data;
        // set user property values here
        // set user property values
        $user->firstname = $userData->firstname;
        $user->lastname = $userData->lastname;
        $user->email = $userData->email;
        $user->password = $userData->password;
        $user->role = $userData->role;
        $user->id = $userData->id;
 
        // update user will be here
        // update the user record
        if($user->update()){
            // regenerate jwt will be here
            // we need to re-generate jwt because user details might be different
        $token = array(
            "iat" => $issued_at,
            "exp" => $expiration_time,
            "iss" => $issuer,
            "data" => array(
                "id" => $user->id,
                "email" => $user->email
            )
 );
 
 $jwt = JWT::encode($token, $key, 'HS256');
  
 // set response code
 http_response_code(200);
  
 // response in json format
 echo json_encode(
         array(
             "status" =>http_response_code(200),
             "data" => $userData
        )
     );

}

// message if unable to update user
else{


    // set response code
    http_response_code(401);
 
    // show error message
    echo json_encode(array("message" => "Unable to update user."));
}
    }catch (Exception $e){// catch failed decoding will be here
 

    // $logger = new Logger('update');
    // $logger->pushHandler(new StreamHandler(__DIR__ . '/app.log', Logger::DEBUG));
    // $logger->error();
    error_log($e->getMessage());

    // set response code
    http_response_code(401);
 
    // show error message
    echo json_encode(array(
        "message" => "Access denied.",
        "error" => $e->getMessage()
    ));
}  
}else{
    // show error message if jwt is empty
    // set response code
    http_response_code(401);
 
    // tell the user access denied
    echo json_encode(array("message" => "Access denied2."));
}}
?>