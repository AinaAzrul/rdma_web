<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
/*include_once '../config/database.php';
include_once 'master.php';*/
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare master object
$master = new Master($db);
 
// set ID property of record to read
$master->Asset_no = isset($_GET['Asset_no']) ? $_GET['Asset_no'] : die();
  
// read the details of master to be edited
$master->readOne();
  
if($master->Entry_id!=null){
    // create array
    $master_arr = array(
        "Entry_id" =>  $master->Entry_id,
        "Asset_no" => $master->Asset_no,
        "Asset_desc" => $master->Asset_desc,
        "Taken_by" => $master->Taken_by,
        "Date_taken" => $master->Date_taken,
        "Return_by" => $master->Return_by,
        "Date_return" => $master->Date_return,
        "Remarks" => $master->Remarks,
        "Category" => $master->Category,
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($master_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user master does not exist
    echo json_encode(array("message" => "Asset master does not exist."));
}

?>