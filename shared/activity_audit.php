<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require '../vendor/autoload.php';

    use \Firebase\JWT\JWT;
    use \Firebase\JWT\KEY;  

function save_log($log){
    

    // get database connection
 $database = new Database();
 $db = $database->getConnection();
 $data = json_decode(file_get_contents("php://input"));

 $jwt = $data->token;

 //decode token to get user session id
 $key = "33F06AED8BF74357226AB8EDD16F684FC12E2948C5F818BAB1B2C8E56518630D";
 $decoded = JWT::decode($jwt, new key($key,'HS256'));
$user_id = $decoded->data->id;
$action_made = $data->method;
//  print_r($data->method);
//  print_r($log);

 $sql = "INSERT INTO rdma_audit (user_id , action, details ) VALUES ('{$user_id}','{$action_made}', '{$log}')";
 $stmt = $db->prepare($sql);
 $stmt->execute();
 if(!$stmt){
     // set response code - 503 service unavailable
     http_response_code(503);
     // tell the user
     echo json_encode(array("message" => "Unable to create asset."));
 }
}
