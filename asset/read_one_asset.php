<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
/*include_once '../config/database.php';
include_once 'asset.php';*/

$requestMethod = $_SERVER["REQUEST_METHOD"];
$strErrorDesc = '';

if (strtoupper($requestMethod) == 'GET'){
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare asset object
$asset = new Asset($db);
  
// set ID property of record to read
$asset->Asset_no = isset($_GET['Asset_no']) ? $_GET['Asset_no'] : die();
  
// read the details of asset to be edited
$asset->readOne();

if($asset->id!= null){
 
    // create array
    $asset_arr = array(
        
        "Asset_no" =>  $asset->Asset_no,
        "Asset_desc" => $asset->Asset_desc,
        "Category" => $asset->Category,
        "Location" => $asset->Location,
        "CalibDate_start" => $asset->CalibDate_start,
        "CalibDate_end" => $asset->CalibDate_end,
        "Company_name" => $asset->Company_name,             
  
    );

}
  
else{
    
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user asset does not exist
    echo json_encode(array("message" => "asset does not exist."));
}
}

else{
    $strErrorDesc = 'Method not supported';
    $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
}

// send output
if (!$strErrorDesc) {
    // set response code - 200 OK
    http_response_code(200);
  
    // show assets data in json format
    echo json_encode($asset_arr);
} else {
    // set response code - 422 unprocessable Entity 
    http_response_code(422);
  
    // tell the user no assets found
    echo json_encode(
        array("error" => "Method not supported."));
}
?>