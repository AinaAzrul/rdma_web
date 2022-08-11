<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once("shared/activity_audit.php");

function get_user(){
//include_once '../config/core.php';

// instantiate database and user object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$user = new User($db);


// read users will be here
// query users
$stmt = $user->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // users array
    $users_arr=array();
    $users_arr["records"]=array();
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $user_item=array(
            "id" => $id,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "role" => $role
        );
  
        array_push($users_arr["records"], $user_item);
    }
  
    // show users data in json format
    echo json_encode(array(
    "status" =>http_response_code(200),
    "data"=>$users_arr));
}
  
// no users found will be here
else{
  
    // tell the user no users found
    echo json_encode( 
        // set response code - 404 Not found
        array(
            "status" =>http_response_code(404),
            "message" => "No users found.")
    );
} 
}
    


