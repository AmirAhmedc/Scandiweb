<?php 

namespace MyApp\classes;
// include_once '../private/initialize.php'; 
use MyApp\classes\Validate;

$productQuery = new Validate($database);
$response = $productQuery->checkSkuExists($_GET['sku']);

header('Content-Type: application/json');
echo json_encode($response);
