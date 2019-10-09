<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'class/DbCon.php';

$httpMethod =  $_SERVER['REQUEST_METHOD'];

$uri = $_SERVER['REQUEST_URI'];

$tempUri = explode('/', $uri);
foreach($tempUri AS $key => $val) {
  unset($tempUri[$key]);
  if($val === 'serversideprogrammering') {
    break;
  }
}

$uri = array_values($tempUri);

//echo '<pre>';
//var_dump($uri);
//echo '</pre>';

$headers = apache_request_headers();
$accept = str_replace(' ', '',$headers['Accept']);
$accept = explode(',',$accept);

//echo '<pre>';
//var_dump($accept);
//echo '</pre>';

switch ($httpMethod) {
  case 'GET':
  // Looking at the URI for which resources we're asking for
    if($uri[0] === 'products') { // If its products it'll run this following code
      // Creates an instance of the DbCon class
      $dbCon = new DbCon();

      // Get the return value from the getAllProducts function
      $products = $dbCon->getAllProducts();

      if(in_array('application/json',$accept)){
          header('Access_Control-Allow-Origin: *');
          header("Content-Type: application/json; charset=utf-8");
          echo json_encode($products);
      } else if(in_array('application/xml',$accept)) {

      } else {
        http_response_code(412);
        die('412 - Wrong accept type. Only JSON and XML supported!');
      }
    } else if ($uri[0] === 'product' && $uri[1] === 'id' && !empty($uri[2])) {
      // Creates an instance of the DbCon class
      $dbCon = new DbCon();

      // Get the return value from the getAllProducts function
      $product = $dbCon->getProductById($uri[2]);

      if(in_array('application/json',$accept)){
        header('Access_Control-Allow-Origin: *');
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($product);
      } else if(in_array('application/xml',$accept)) {

      } else {
        http_response_code(412);
        die('412 - Wrong accept type. Only JSON and XML supported!');
      }
    }
    break;
  case 'POST':
    echo 'POST REQ';
    break;

  case 'PUT':
    echo 'PUT REQ';
    break;

  case '"DELETE"':
    echo '"DELETE REQ"';
    break;
  default:
    http_response_code(405);
    die('405 - Bad request method!');
}

// Loops through each product
//foreach($products AS $prod) {
  //echo $prod->getName();
  //echo '<br>';
//};
echo "<h1>Hello World!</h1>";
?>
