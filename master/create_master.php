<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header(
    "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
);
/* 
// get database connection
include_once '../config/database.php';
  
// instantiate master object
include_once 'master.php';
  */

function create_master()
{
    $database = new Database();
    $db = $database->getConnection();

    $master = new Master($db);

    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    // make sure data is not empty
    if (
        !empty($data->data->Asset_no) &&
        !empty($data->data->Asset_desc)
        // && !empty($data->data->Taken_by) &&
        // !empty($data->data->Date_taken)
    ) {
        $u = $data->data;

        // set master property values
        $master->Asset_no = $u->Asset_no;
        $master->Asset_desc = $u->Asset_desc;
        $master->Taken_by = $u->Taken_by;
        $master->Date_taken = $u->Date_taken;
        $master->Return_by = $u->Return_by;
        $master->Date_return = $u->Date_return;
        $master->Remarks = $u->Remarks;
        $master->Category = $u->Category;

        // create the master
        if ($master->create()) {
            //add to activity log
            $details = "Master list entry for asset number $master->Asset_no created";
            save_log($details);

            // tell the user
            echo json_encode([
                "status" => http_response_code(201),
                "data" => $master,
            ]);
        }

        // if unable to create the master, tell the user
        else {
            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(["message" => "Unable to create master."]);
        }
    }

    // tell the user data is incomplete
    else {
        // set response code - 400 bad request
        http_response_code(400);

        // tell the user
        echo json_encode([
            "message" => "Unable to create master. Data is incomplete.",
        ]);
    }
}
?>
