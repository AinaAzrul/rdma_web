<?php
// required header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
/*include_once '../config/database.php';
include_once 'asset.php';*/
function read_asset(){
$requestMethod = $_SERVER["REQUEST_METHOD"];
$strErrorDesc = '';

// instantiate database and asset object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$asset = new Asset($db);
  
// query assets
$stmt = $asset->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // products array
    $assets_arr=array();
    $assets_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        //explode the values in First_calib array into separate values
        $message = $row['First_calib'];
        $arr = explode(",", $message);
        $CalibDate_start = $arr[0];
        $CalibDate_end = $arr[1];
        $Company_name = $arr[2];

        //display the values
        // $asset_item=array(
        //     "Asset_no" => $Asset_no,
        //     "Asset_desc" => html_entity_decode($Asset_desc),
        //     "Category" => $Category,
        //     "Location" => $Location,
        //     "CalibDate_start" => $CalibDate_start,
        //     "CalibDate_end" => $CalibDate_end,
        //     "Company_name" => $Company_name,
        // );

        $asset_item=array(
            "Asset_no" => $Asset_no,
            "Asset_desc" => html_entity_decode($Asset_desc),
            "Category" => $Category,
            "Location" => $Location,
            "First_calib" => $First_calib,
            "Second_calib" => $Second_calib,
            "Third_calib" => $Third_calib,
        );
  
        array_push($assets_arr["records"], $asset_item);

    }

  
}
else{
     // set response code - 404 Not found
     http_response_code(404);
  
     // tell the user no assets found
     echo json_encode(
         array("message" => "No assets found."));
    return false;
}

  
// else{
//     $strErrorDesc = 'Method not supported';
//     $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
// }

// send output
if (!$strErrorDesc) {
  
    // show assets data in json format
    echo json_encode(array(
        "status" =>http_response_code(200),
        "data"=>$assets_arr));

} else {
    // set response code - 422 unprocessable Entity 
    http_response_code(422);
  
    // tell the user no assets found
    echo json_encode(
        array("error" => "Method not supported."));
}
}
?>