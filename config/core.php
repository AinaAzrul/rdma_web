<?php

//bootstrap config with the main files
//connect all the main files 
define("PROJECT_ROOT_PATH", __DIR__ ."/../" );
 
// include main configuration file
//so other files dint need to declare database.php
include __DIR__ . "/database.php"; 


// include the user file
require_once PROJECT_ROOT_PATH . "/users/user.php";

// include the master file
require_once PROJECT_ROOT_PATH . "/master/master.php";

// include the master file
require_once PROJECT_ROOT_PATH . "/asset/asset.php";

// include the master file
require_once PROJECT_ROOT_PATH . "/shared/utilities.php";

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

// show error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
  
// home page url
$home_url="http://localhost/rdma_web/";
  
// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;
  
// set number of records per page
$records_per_page = 3;
  
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

// set your default time-zone
date_default_timezone_set('Asia/Kuala_Lumpur');
 

// variables used for jwt
$key = "33F06AED8BF74357226AB8EDD16F684FC12E2948C5F818BAB1B2C8E56518630D";
$issued_at = time();
$expiration_time = $issued_at + (60 * 60); // valid for 1 hour
$issuer = "http://localhost/rdma_web";

?>