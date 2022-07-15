<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here
// include database and object files
//include_once '../config/database.php';
//include_once 'master.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$master = new Master($db);
  
// read products will be here
// query products
$stmt = $master->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // products array
    $masters_arr=array();
    $masters_arr["records"]=array();
  
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
            "Category"=> $Category
        );
  
        array_push($masters_arr["records"], $master_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode(array(
        "status" =>http_response_code(200),
        "data"=>$masters_arr));
}
  
// no products found will be here
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}