<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
/*include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once 'master.php';*/
  
// utilities
$utilities = new Utilities();
  
// instantiate database and master object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$master = new Master($db);
  
// query masters
$stmt = $master->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // masters array
    $masters_arr=array();
    $masters_arr["records"]=array();
    $masters_arr["paging"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $master_item=array(
            "Entry_id" => $Entry_id,
            "Asset_no" => $Asset_no,
            "Asset_desc" => html_entity_decode($Asset_desc),
            "Taken_by" => $Taken_by,
            "Date_taken" => $Date_taken,
            "Return_by" => $Return_by,
            "Date_return" => $Date_return,
            "Remarks" => $Remarks,
            "Category" => $Category
        );
  
        array_push($masters_arr["records"], $master_item);
    }
  
  
    // include paging
    $total_rows=$master->count();
    $page_url="{$home_url}master/read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $masters_arr["paging"]=$paging;
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($masters_arr);
}
  
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user masters does not exist
    echo json_encode(
        array("message" => "No master item found.")
    );
}
?>