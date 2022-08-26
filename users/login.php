<?php
include_once "user.php";
require "../vendor/autoload.php";
use Firebase\JWT\JWT;
// use rdma\model;

// required headers

//both of these include_once will be ok if deleted
// require __DIR__."config/core.php";

function login()
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header(
        "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
    );

    // set your default time-zone
    date_default_timezone_set("Asia/Kuala_Lumpur");

    // variables used for jwt
    $key = "33F06AED8BF74357226AB8EDD16F684FC12E2948C5F818BAB1B2C8E56518630D";
    $issued_at = time();
    $expiration_time = $issued_at + 60 * 60 * 24; // valid for 1 hour
    $issuer = "http://localhost/rdma_web";

    // get database connection
    $database = new Database();
    $db = $database->getConnection();

    // instantiate user object
    $user = new User($db);

    // check email existence here
    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    error_log("data" . $data->data->email);
    // error_log("data".$data);
    // set product property values
    $user->email = $data->data->email;
    $email_exists = $user->emailExists();

    // generate jwt will be here
    // check if email exists and if password is correct
    if (
        $email_exists &&
        password_verify($data->data->password, $user->password)
    ) {
        $token = [
            "iat" => $issued_at,
            "exp" => $expiration_time,
            "iss" => $issuer,
            "data" => [
                "id" => $user->id,
                "role" => $user->role,
            ],
        ];

        // generate jwt
        $jwt = JWT::encode($token, $key, "HS256");
        echo json_encode([
            "status" => http_response_code(200),
            "message" => "Successful login.",
            "jwt" => $jwt,
        ]);
    }

    // login failed will be here
    // login failed
    else {
        //http_response_code(401);
        // tell the user login failed
        echo json_encode([
            "status" => http_response_code(401),
            "message" => "Login failed.",
        ]);
    }
}
?>
