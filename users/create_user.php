<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// database connection will be here
// files needed to connect to database
//include_once '../config/database.php';
//include_once 'user.php';
 
function create_user(){
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$user = new User($db);
 
// submitted data will be here
// get posted data
$data = json_decode(file_get_contents("php://input"));

$dataUser = $data->data; 
// set product property values
$user->firstname = $dataUser->firstname;
$user->lastname = $dataUser->lastname;
$user->email = $dataUser->email;
$user->password = $dataUser->password;
$user->role = $dataUser->role;
 
// use the create() method here
// create the user
if(
    !empty($user->firstname) &&
    !empty($user->email) &&
    !empty($user->password) &&
    $user->create()
){
 
    // set response code
    http_response_code(200);

    // response in json format
    echo json_encode(
        array(
            "status" =>http_response_code(200),
            "data" => "User was created."
        )
        );
}
 
// message if unable to create user
else{
 
    // set response code
    http_response_code(400);

    // response in json format
    echo json_encode(
        array(
            "status" =>http_response_code(400),
            "data" => "Unable to create user."
       )
    );
}
}
?>