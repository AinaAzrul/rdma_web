<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
/*include_once '../config/core.php';
include_once '../config/database.php';
include_once 'master.php';*/

// instantiate database and master object
$database = new Database();
$db = $database->getConnection();

// initialize object
$master = new Master($db);

// get keywords
$keywords = isset($_GET["s"]) ? $_GET["s"] : "";

// query masters
$stmt = $master->search($keywords);
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {
    // masters array
    $masters_arr = [];
    $masters_arr["records"] = [];

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $master_item = [
            "Entry_id" => $Entry_id,
            "Asset_no" => $Asset_no,
            "Asset_desc" => html_entity_decode($Asset_desc),
            "Taken_by" => $Taken_by,
            "Date_taken" => $Date_taken,
            "Return_by" => $Return_by,
            "Date_return" => $Date_return,
            "Remarks" => $Remarks,
            "Category" => $Category,
        ];

        array_push($masters_arr["records"], $master_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show masters data
    echo json_encode($masters_arr);
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no masters found
    echo json_encode(["message" => "No master item found."]);
}
?>
