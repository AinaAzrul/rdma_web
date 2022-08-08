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

function update_asset(){
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare asset object
$asset = new Asset($db);


// get id of asset to be edited
$data = json_decode(file_get_contents("php://input"));
// set ID property of asset to be edited
$info = $data->data;
$asset->Asset_no = $info->Asset_no;

// set asset property values

$asset->Asset_desc = $info->Asset_desc;
$asset->Category = $info->Category;
$asset->Location = $info->Location;
  
// update the asset
if($asset->updateAsset()){ 
    // set response code - 200 ok
    http_response_code(200);
  
    // response in json format
     echo json_encode(
    array(
        "status" =>http_response_code(200),
        "data" => $asset
   )
    );
}

  
// if unable to update the asset, tell the user
else{
    echo json_encode(
        array(
            "status" =>http_response_code(503),
            "data" => $asset,
            "message" => "Unable to update asset."
       )
    );

}
};

function update_calib(){
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
      
    // prepare asset object
    $asset = new Asset($db);
    
    // get id of asset to be edited
    $data = json_decode(file_get_contents("php://input"));
    // set ID property of asset to be edited
    $info = $data->data; 
    $asset->id = $info->id;
    
    // set asset property values
    $asset->id = $info->id;
    $asset->Calib_no = $info->Calib_no;
    $asset->Start_date = $info->Start_date;
    $asset->End_date = $info->End_date;
    $asset->Company_name = $info->Company_name;
      
    // update the asset
    if($asset->updateCalib()){
          // response in json format
    echo json_encode(
    array(
        "status" =>http_response_code(200),
        "data" => $asset
        )
    );
    }
    
      
    // if unable to update the asset, tell the user
    else{
        echo json_encode(
            array(
                "status" =>http_response_code(503),
                "data" => $asset,
                "message" => "Unable to update asset."
           )
        );
    }
    };


?>