<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
/*include_once '../config/database.php';
include_once 'master.php';*/

function update_master(){
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare master object
$master = new Master($db);
  
// get id of master to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of master to be edited
$master->Entry_id = $data->data->Entry_id;

$dataMstr = $data->data;
  
// set master property values
$master->Asset_no = $dataMstr->Asset_no;
$master->Asset_desc = $dataMstr->Asset_desc;
$master->Taken_by = $dataMstr->Taken_by;
$master->Date_taken = $dataMstr->Date_taken;
$master->Return_by = $dataMstr->Return_by;
$master->Date_return = $dataMstr->Date_return;
$master->Remarks = $dataMstr->Remarks;
$master->Category = $dataMstr->Category;

// update the master
if($master->update()){

    //add to activity log
    $details = "Master list entry for asset number $master->Asset_no updated";
    save_log($details);

    // response in json format
 echo json_encode(
    array(
        "status" =>http_response_code(200),
        "data" => $master
   )
    );

}
  
// if unable to update the master, tell the user
else{
  
    echo json_encode(
        array(
            "status" =>http_response_code(503),
            "data" => $master
       )
        );
}
}
?>