<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header(
    "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
);
require "../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\KEY;

function save_log($log)
{
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
    $data = json_decode(file_get_contents("php://input"));

    $jwt = $data->token;

    //decode token to get user session id
    $key = "33F06AED8BF74357226AB8EDD16F684FC12E2948C5F818BAB1B2C8E56518630D";
    $decoded = JWT::decode($jwt, new key($key, "HS256"));
    $user_id = $decoded->data->id;
    $action_made = $data->method;
    //  print_r($data->method);
    //  print_r($log);

    $sql = "INSERT INTO rdma_audit (user_id , action, details ) VALUES ('{$user_id}','{$action_made}', '{$log}')";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    if (!$stmt) {
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(["message" => "Unable to save log."]);
    }
}

function get_log()
{
    // get database connection
    $database = new Database();
    $db = $database->getConnection();

    $audit_arr["records"] = [];

    $query = "SELECT a.user_id, u.firstname, a.action, a.details, a.datetime, u.lastname
		FROM rdma_audit a
		INNER JOIN users u
		ON a.user_id = u.id 
        ORDER BY a.audit_id DESC";

    $stmt = $db->prepare($query);
    $stmt->execute();

    if ($stmt) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $audit_item = [
                "user_id" => $user_id,
                "firstname" => $firstname,
                "action" => $action,
                "details" => html_entity_decode($details),
                "datetime" => $datetime,
                "lastname" => $lastname,
            ];

            array_push($audit_arr["records"], $audit_item);
        }
        // show assets data in json format
        echo json_encode([
            "status" => http_response_code(200),
            "data" => $audit_arr,
        ]);
    } else {
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(["message" => "Unable to save log."]);
    }
}
