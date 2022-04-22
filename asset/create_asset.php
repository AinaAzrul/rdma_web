<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
//include_once '../config/database.php';
  
// instantiate asset object
//include_once 'asset.php';
  
$database = new Database();
$db = $database->getConnection();
  
$asset = new Asset($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));

//implode the data receive from user input into one array
$dateStart = $data->CalibDate_start;
$dateEnd = $data->CalibDate_end;
$compName = $data->Company_name;
$arr = array($dateStart,$dateEnd,$compName);
$First_calib = implode(',', $arr);


// make sure data is not empty
if(
    !empty($data->Asset_no) &&
    !empty($data->Asset_desc) 
){
  
    // set asset property values
    $asset->Asset_no = $data->Asset_no;
    $asset->Asset_desc = $data->Asset_desc;
    $asset->Category = $data->Category;
    $asset->Location = $data->Location;
    $asset->CalibDate_start = $data->CalibDate_start;
    $asset->CalibDate_end = $data->CalibDate_end;
    $asset->Company_name = $data->Company_name;
    $asset->First_calib = $First_calib;
    
  
    // create the asset
    if($asset->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "asset was created."));
    }
  
    // if unable to create the asset, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create asset."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create asset. Data is incomplete."));
}
?>