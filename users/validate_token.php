<?php
// required headers
// header("Access-Control-Allow-Origin: http://localhost/rdma_web/");
// header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Max-Age: 3600");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required to decode jwt
// include_once '../config/core.php';
require "../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\KEY;

function authToken()
{
    $key = "33F06AED8BF74357226AB8EDD16F684FC12E2948C5F818BAB1B2C8E56518630D";
    // retrieve gieve jwt here
    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    // get jwt
    $jwt = isset($data->token) ? $data->token : "";
    // $jwt= substr(getallheaders()["Authorization"], 7);

    // if jwt is not empty
    if ($jwt) {
        // if decode succeed, show user details
        try {
            // decode jwt
            $decoded = JWT::decode($jwt, new key($key, "HS256"));

            // show user details
            //omit echo
            return json_encode([
                "status" => http_response_code(200),
                "message" => "Access granted.",
                "data" => $decoded->data,
            ]);
        } catch (Exception $e) {
            // if decode fails, it means jwt is invalid
            // set response code
            // http_response_code(401);

            // tell the user access denied  & show error message
            return json_encode([
                "status" => 401,
                "message" => "Access denied.",
                "error" => $e->getMessage(),
            ]);
            // return false;
        }
    }

    // show error message if jwt is empty
    else {
        // set response code
        http_response_code(401);

        // tell the user access denied
        return json_encode(["message" => "Empty token"]);
        // return false;
    }
}
?>
