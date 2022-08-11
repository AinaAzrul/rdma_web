<?php

require_once('vendor/autoload.php');

//User component
require_once('users/validate_token.php');
require_once('users/login.php');
require_once('users/get_user.php');
require_once('users/update_user.php');
require_once('users/delete_user.php');
require_once('users/create_user.php');

//master component
require_once('master/read_master.php');
require_once('master/update_master.php');
require_once('master/create_master.php');
require_once('master/delete_master.php');

//Asset component
require_once('asset/read_asset.php');
require_once('asset/create_asset.php');
require_once('asset/delete_asset.php');
require_once('asset/update_asset.php');
require_once('asset/add_calib_asset.php');

//Logging
require_once('shared/activity_audit.php');

// require_once('asset/asset.php');
require __DIR__ . "/config/core.php";
use Firebase\JWT\JWT;
use Firebase\JWT\KEY;

//Monolog error logging
require __DIR__ . '/vendor/autoload.php';
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

//symfony 
// use Symfony\Component\Console\Application;

// $application = new Application();

// // ... register commands

// $application->run();
// $application->add(new GenerateAdminCommand());

////FINAL EDIT
$testpoint = new TestPoint();


class TestPoint{
    private $user, $asset, $master;
    
    public function __construct(){
    //    $database = new Database();
    //     $db = $database->getConnection();
    //      $this->user = new authUser();
    //     $this->asset = new Asset($db);
    //     $this->master = new Master($db);
        
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, POST, DELETE, OPTIONS");
    header("Access-Control-Max-Age: 86400");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    $input = json_decode(file_get_contents("php://input"));
    $meth = $input->{'method'};
    $jwt = isset($input->token) ? $input->token : "";

    
    // if ($this->authGuard()==401){
    //     echo authToken();
    //     error_log("false");
    //     login();
    // }
    // else {
    //     error_log("valid token");
    // }

    //Routes
    switch($meth){

        case "login":
           login();
        break;

        case "validate_token":
             echo authToken();
         break;

        //for user class
        case "get_user": 
            // get_user();
            if ($this->authGuard()==200){
               get_user();
               
            }
            else{
                echo authToken();
            }
        break;

        case "update_user":
            update_user();
         break;

         case "delete_user":
            delete_user();
         break;

         case "create_user":
            create_user();
         break;

         //for master class
         case "read_master":
            read_master();
         break;

         case "create_master":
            create_master();
         break;

         case "update_master":
            update_master();
         break;

         case "delete_master":
            delete_master();
         break;

         //for Asset class
         case "read_asset";
            read_asset();
         break;

         case "create_asset";
            create_asset();
         break;

         case "delete_asset";
            delete_asset();
         break;
         
         case "update_asset";
            update_asset();
         break;

         case "update_calib";
            update_calib();
         break;

         case "create_calib";
            create_calib();
         break;

         case "deleteCalib";
            deleteCalib();
         break;

        default:
        break;
    
}

}


public function authGuard() {
    // Auth token
    $result = authToken();
    $try = json_decode($result,true);
    return $try['status'];
    // $result = 44;
    // echo json_decode(var_dump($result)); 
    // if($result == false){
    //     error_log("Invalid token");
    //     return false;
    // }
    // else {
    //     error_log("valid token");
    //     return true;
    // }
}
}


// // required headers (15/7)
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, POST, DELETE, OPTIONS");
// header("Access-Control-Max-Age: 86400");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// $input = json_decode(file_get_contents("php://input"));
// $meth = $input->method;
// $url = "http://localhost/rdma_web/api.php";


// if(true){
// //execute method
// switch($meth){

//     case "login":
//         include 'users/login.php';
//     break;

//     case "validate_token":
//         include 'users/validate_token.php';
//     break;

//     //for user class
//     case "get_user":
            
//             // $result= $this->user->validate_token();
//            include 'users/get_user.php';
//            //header('Location: http://localhost/rdma_web/users/get_user.php');
//            /*$url = 'http://localhost/rdma_web/users/get_user.php'; 
//            redirect($url);*/
//        break;

//     case "create_user":
//             include 'users/create_user.php';
//         break;
    
//     case "delete_user":
//             include 'users/delete_user.php';
//         break;
        
//     case "search_user":
//             include 'users/search_user.php';
//         break;

//     case "update_user":
//             //header('Location: http://localhost/rdma_web/users/update_user.php');
//             include 'users/update_user.php';
//         break;

//     //for master class
//     case "read_master":
//         include 'master/read_master.php';
//     break;

//     case "create_master":
//         include 'master/create_master.php';
//     break;

//     case "delete_master":
//         include 'master/delete_master.php';
//     break;

//     case "update_master":
//         include 'master/update_master.php';
//     break;

//     case "search_master":
//         include 'master/search_master.php';
//     break;

//     case "read_one_master":
//         include 'master/read_one_master.php';
//     break;

//     case "read_paging_master":
//         include 'master/read_paging_master.php';
//     break;

//     //for asset class
//     case "read_asset":
//         include 'asset/read_asset.php';
//     break;

//     case "create_asset":
//         include 'asset/create_asset.php';
//     break;

//     case "delete_asset":
//         include 'asset/delete_asset.php';
//     break;

//     case "update_asset":
//         include 'asset/update_asset.php';
//     break;

//     case "search_asset":
//         include 'asset/search_asset.php';
//     break;

//     case "read_one_asset":
//         include 'asset/read_one_asset.php';
//     break;

//     case "read_paging_asset":
//         include 'asset/read_paging_asset.php';
//     break;

//     case "add_calib_asset":
//         include 'asset/add_calib_asset.php';
//     break;

// default:
//         break;
        
// }
// }

// else{
//     // No token was able to be extracted from the authorization header
//     header('HTTP/1.0 400 Bad Request');
//     exit;
// }



?>