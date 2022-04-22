<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 /* 
// get database connection
include_once '../config/database.php';
  
// instantiate master object
include_once 'master.php';
  */
$database = new Database();
$db = $database->getConnection();
  
$master = new Master($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));


// make sure data is not empty
if(
    !empty($data->Asset_no) &&
    !empty($data->Asset_desc) &&
    !empty($data->Taken_by) &&
    !empty($data->Date_taken) 
   
){
  
    // set master property values
    $master->Asset_no = $data->Asset_no;
    $master->Asset_desc = $data->Asset_desc;
    $master->Taken_by = $data->Taken_by;
    $master->Date_taken = $data->Date_taken;
    $master->Return_by = $data->Return_by;
    $master->Date_return = $data->Date_return;
    $master->Remarks = $data->Remarks;
    $master->Category= $data->Category;

    // create the master
    if($master->create()){
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "master item was created."));
    }
  
    // if unable to create the master, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create master."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create master. Data is incomplete."));
}
?>