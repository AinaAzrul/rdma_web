<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required to encode json web token
//include_once '../config/core.php';
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\KEY;
 
// files needed to connect to database
/*include_once '../config/database.php';*/
//include_once 'user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate user object
$user = new User($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// get jwt
//$jwt=isset($data->jwt) ? $data->jwt : "";
$jwt = substr(getallheaders()["Authorization"], 7);

// decode jwt here
// if jwt is not empty
if($jwt){
 
    // if decode succeed, show user details
    try {
        
        // decode jwt
        $decoded = JWT::decode($jwt, new Key($key,'HS256'));
        
        // set user property values here
        // set user property values
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->password = $data->password;
$user->role = $data->role;
$user->id = $data->id;
 
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
             "message" => "User was updated.",
             "jwt" => $jwt
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
 

    $logger = new Logger('update');
    $logger->pushHandler(new StreamHandler(__DIR__ . '/app.log', Logger::DEBUG));
    $logger->error($e->getMessage());

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
    echo json_encode(array("message" => "Access denied."));
}
?>