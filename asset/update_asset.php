<?php
//function to update all values with binded calibration values

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
/*include_once '../config/database.php';
include_once 'asset.php';*/
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare asset object
$asset = new Asset($db);
  
// get id of asset to be edited
$data = json_decode(file_get_contents("php://input"));
// set ID property of asset to be edited
$asset->id = $data->id;

// set asset property values
$asset->Asset_no = $data->Asset_no;
$asset->Asset_desc = $data->Asset_desc;
$asset->Category = $data->Category;
$asset->Location = $data->Location;
$asset->First_calib = $data->First_calib;
$asset->Second_calib = $data->Second_calib;
$asset->Third_calib = $data->Third_calib;
  
// update the asset
if($asset->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "asset was updated."));
}
  
// if unable to update the asset, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update asset."));
}
?>