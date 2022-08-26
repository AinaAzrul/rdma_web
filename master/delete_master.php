<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header(
    "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
);

// include database and object file
/*include_once '../config/database.php';
 include_once 'master.php';*/

function delete_master()
{
    // get database connection
    $database = new Database();
    $db = $database->getConnection();

    // prepare master object
    $master = new Master($db);

    // get master id
    $data = json_decode(file_get_contents("php://input"));
    $dataMstr = $data->data;

    // set master id to be deleted
    $master->Entry_id = $dataMstr->Entry_id;

    // delete the master

    if ($master->delete() == true) {
        //add to activity log
        $details = "Master list entry id $master->Entry_id deleted";
        save_log($details);

        // response in json format
        echo json_encode([
            "status" => http_response_code(200),
            "data" => "Entry id: " . $dataMstr->Entry_id,
        ]);
    }

    // if unable to delete the master
    else {
        // response in json format
        echo json_encode([
            "status" => http_response_code(503),
            "data" => $master,
            "message" => "Unable to delete master.",
        ]);
    }
}
?>
