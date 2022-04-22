<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
/*include_once '../config/database.php';
include_once 'master.php';*/
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare master object
$master = new Master($db);
  
// get id of master to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of master to be edited
$master->Entry_id = $data->Entry_id;
  
// set master property values
$master->Asset_no = $data->Asset_no;
$master->Asset_desc = $data->Asset_desc;
$master->Taken_by = $data->Taken_by;
$master->Date_taken = $data->Date_taken;
$master->Return_by = $data->Return_by;
$master->Date_return = $data->Date_return;
$master->Remarks = $data->Remarks;
$master->Category = $data->Category;

// update the master
if($master->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "master was updated."));
}
  
// if unable to update the master, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update master."));
}
?>