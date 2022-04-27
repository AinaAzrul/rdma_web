<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/rdma_web/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('vendor/autoload.php');
require __DIR__ . "/config/core.php";
use Firebase\JWT\JWT;
use Firebase\JWT\KEY;

//Monolog error logging
require __DIR__ . '/vendor/autoload.php';
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/*$logger = new Logger('channel-name');
$logger->pushHandler(new StreamHandler(__DIR__ . '/app.log', Logger::DEBUG));
$logger->info('This is a log! ^_^ ');
$logger->warning('This is a log warning! ^_^ ');
$logger->error('This is a log error! ^_^ ');*/

//parse URL to get the mthod name
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
$method = $uri[4];

//validate jwt token
$jwt= substr(getallheaders()["Authorization"], 7);
$now = new DateTimeImmutable();
$serverName = "http://localhost/rdma_web";

if($jwt){
 
    try {
        // decode jwt
        $decoded = JWT::decode($jwt, new key($key,'HS256'));
        
        // if decode fails, it means jwt is invalid
       if ($decoded->iss !== $serverName)
        {
            header('HTTP/1.1 401 Unauthorized');
            // show error details
            echo json_encode(array(
            "error" => "Access denied, Incorrect Token."
        ));

        if($decoded->exp < $now->getTimestamp())
        {
            header('HTTP/1.1 401 Unauthorized');
            // if token expires, automatically logout
            echo json_encode(array(
            "logout" => "Token has expired, please login again."
            //logout function call from frontend 
            ));
        }
            exit;
        }

        // set response code
        http_response_code(200);
 
        // show user details
        echo json_encode(array(
            "message" => "Access granted."
        ));
        $result = true;

    }catch (Exception $e){

    //add logging
    $logger = new Logger('token_validation');
    $logger->pushHandler(new StreamHandler(__DIR__ . '/app.log', Logger::DEBUG));
    $logger->error($e->getMessage());
/*
// setting error logging to be active
ini_set("log_errors", TRUE); 
  
// setting the logging file in php.ini
ini_set('error_log', $log_file);
  
// logging the error
error_log($error_message);
    */
    
    // set response code
    http_response_code(401);
    // tell the user access denied  & show error message
    echo json_encode(array(
        "message" => "Access denied.",
        "error" => $e->getMessage()
    ));
    $result = false; 
}
}else{
 
    // set response code
    http_response_code(401);
 
    // tell the user access denied
    echo json_encode(array("message" => "Access denied."));
    $result = false;
}

if ($result == true){
    
//execute method
switch($method){

    //for user class
    case "get_user":
           include 'users/get_user.php';
           //header('Location: http://localhost/rdma_web/users/get_user.php');
           /*$url = 'http://localhost/rdma_web/users/get_user.php'; 
           redirect($url);*/
       break;

    case "create_user":
            include 'users/create_user.php';
        break;
    
    case "delete_user":
            include 'users/delete_user.php';
        break;
        
    case "search_user":
            include 'users/search_user.php';
        break;

    case "update_user":
            //header('Location: http://localhost/rdma_web/users/update_user.php');
            include 'users/update_user.php';
        break;

    //for master class
    case "read_master":
        include 'master/read_master.php';
    break;

    case "create_master":
        include 'master/create_master.php';
    break;

    case "delete_master":
        include 'master/delete_master.php';
    break;

    case "update_master":
        include 'master/update_master.php';
    break;

    case "search_master":
        include 'master/search_master.php';
    break;

    case "read_one_master":
        include 'master/read_one_master.php';
    break;

    case "read_paging_master":
        include 'master/read_paging_master.php';
    break;

    //for asset class
    case "read_asset":
        include 'asset/read_asset.php';
    break;

    case "create_asset":
        include 'asset/create_asset.php';
    break;

    case "delete_asset":
        include 'asset/delete_asset.php';
    break;

    case "update_asset":
        include 'asset/update_asset.php';
    break;

    case "search_asset":
        include 'asset/search_asset.php';
    break;

    case "read_one_asset":
        include 'asset/read_one_asset.php';
    break;

    case "read_paging_asset":
        include 'asset/read_paging_asset.php';
    break;

    case "add_calib_asset":
        include 'asset/add_calib_asset.php';
    break;

default:
        break;
}

}
else{
    // No token was able to be extracted from the authorization header
    header('HTTP/1.0 400 Bad Request');
    exit;
}

/*
 function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
}*/
?>