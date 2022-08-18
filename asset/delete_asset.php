<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object file
/*include_once '../config/database.php';
include_once 'asset.php';*/

function delete_asset(){
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare asset object
$asset = new Asset($db);
  
// get asset Asset_no
$data = json_decode(file_get_contents("php://input"));
  
// set asset Asset_no to be deleted
$asset->Asset_no = $data->data->Asset_no;
  
// delete the asset
if($asset->delete()==true){
    //add to log
    $details = "Asset number $asset->Asset_no deleted";
    save_log($details);
  
     // response in json format
     echo json_encode(
        array(
            "status" =>http_response_code(200),
            "data" => $asset
       )
    );
}
  
// if unable to delete the asset
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete asset."));
}
}

function delete_calib(){
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
      
    // prepare asset object
    $asset = new Asset($db);
      
    // get asset Asset_no
    $data = json_decode(file_get_contents("php://input"));
      
    // set asset Asset_no to be deleted
    $asset->id = $data->data->id;
    $asset->asset_no = $data->data->asset_no;
    $asset->Calib_no = $data->data->Calib_no;

      
    // delete the asset
    if($asset->delete_calib()==true){

        //add to activity log
        $details = "Calibration number $asset->Calib_no from asset $asset->asset_no deleted";
        save_log($details);

      
         // response in json format
         echo json_encode(
            array(
                "status" =>http_response_code(200),
                "data" => $asset->id
           )
        );
    }
      
    // if unable to delete the asset
    else{
        // set response code - 503 service unavailable
        http_response_code(503);
      
        // tell the user
        echo json_encode(array("message" => "Unable to delete calibration."));
    }}
?>