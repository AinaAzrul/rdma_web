<?php
require('../vendor/autoload.php');

$openapi = \OpenApi\Generator::scan([$_SERVER['DOCUMENT_ROOT'].'/rdma_web/users']);

header('Content-Type: application/json');
echo $openapi->toJSON();