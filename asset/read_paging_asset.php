<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
/*include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once 'asset.php';*/

// utilities
$utilities = new Utilities();

// instantiate database and asset object
$database = new Database();
$db = $database->getConnection();

// initialize object
$asset = new Asset($db);

// query assets
$stmt = $asset->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {
    // assets array
    $assets_arr = [];
    $assets_arr["records"] = [];
    $assets_arr["paging"] = [];

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $asset_item = [
            "id" => $id,
            "Asset_no" => $Asset_no,
            "Asset_desc" => html_entity_decode($Asset_desc),
            "Category" => $Category,
            "Location" => $Location,
            "First_calib" => $First_calib,
            "Second_calib" => $Second_calib,
            "Third_calib" => $Third_calib,
        ];

        array_push($assets_arr["records"], $asset_item);
    }

    // include paging
    $total_rows = $asset->count();
    $page_url = "{$home_url}asset/read_paging.php?";
    $paging = $utilities->getPaging(
        $page,
        $total_rows,
        $records_per_page,
        $page_url
    );
    $assets_arr["paging"] = $paging;

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($assets_arr);
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user assets does not exist
    echo json_encode(["message" => "No assets found."]);
}
?>
