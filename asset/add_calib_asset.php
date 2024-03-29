<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header(
    "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
);

// get database connection
//include_once '../config/database.php';

// instantiate asset object
//include_once 'asset.php';
function create_calib()
{
    $database = new Database();
    $db = $database->getConnection();

    $asset = new Asset($db);

    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    $newData = $data->data;
    //implode the data receive from user input into one array
    // $dateStart = $data->CalibDate_start;ws
    // $dateEnd = $data->CalibDate_end;
    // $compName = $data->Company_name;
    // $arr = array($dateStart,$dateEnd,$compName);
    // $First_calib = implode(',', $arr);

    // make sure data is not empty
    if (!empty($newData->Asset_no)) {
        // set asset property values
        $asset->Asset_no = $newData->Asset_no;
        $asset->Calib_no = $newData->Calib_no;
        $asset->Start_date = $newData->Start_date;
        $asset->End_date = $newData->End_date;
        $asset->Company_name = $newData->Company_name;

        // create the asset
        if ($asset->add_calib()) {
            //add to log
            $details = "Calibration $asset->Calib_no for asset $asset->Asset_no created";
            save_log($details);

            // show products data in json format
            echo json_encode([
                "status" => http_response_code(200),
                "data" => $asset,
            ]);
        }

        // if unable to create the asset, tell the user
        else {
            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(["message" => "Unable to create asset."]);
        }
    }

    // tell the user data is incomplete
    else {
        // set response code - 400 bad request
        http_response_code(400);

        // tell the user
        echo json_encode([
            "message" => "Unable to create asset. Data is incompletereeer.",
        ]);
    }
}
?>
