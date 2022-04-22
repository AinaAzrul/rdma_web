<?php
//function to add new calibration column if there is non created
// if exist, just put in the existing column.

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

//implode the data receive from user input into one array
$Column_name = $data->Column_name;
$dateStart = $data->CalibDate_start;
$dateEnd = $data->CalibDate_end;
$compName = $data->Company_name;
$arr = array($dateStart,$dateEnd,$compName);
$new_calib = implode(',', $arr);

// set ID property of asset to be edited
$asset->id = $data->id;

// update the asset
//Pass column_name and new_calib(the imploded value)
if($asset->add_calib($Column_name,$new_calib)){
  
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