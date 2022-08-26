<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
/*include_once '../config/core.php';
include_once '../config/database.php';
include_once 'asset.php';*/

// instantiate database and asset object
$database = new Database();
$db = $database->getConnection();

// initialize object
$asset = new Asset($db);

// get keywords
$keywords = isset($_GET["s"]) ? $_GET["s"] : "";
$id = isset($_GET["id"]) ? $_GET["id"] : "";

// query assets
$stmt = $asset->search($keywords, $id);
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {
    // assets array
    $assets_arr = [];
    $assets_arr["records"] = [];

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

    // set response code - 200 OK
    http_response_code(200);

    // show assets data
    echo json_encode($assets_arr);
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no assets found
    echo json_encode(["message" => "No assets found."]);
}
?>
