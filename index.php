 <?php
 error_reporting(E_ALL);
 ini_set('display_errors', 1);

 include_once 'class/DbCon.php';

 // Caching not Allow
 header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
 header("Cache-Control: post-check=0, pre-check=0", false);
 header("Pragma: no-cache");

 // Returns the method used. IE GET, POST, PUT or DELETE
 $httpMethod =  $_SERVER['REQUEST_METHOD'];

 // Fetching the URI
 $uri = $_SERVER['REQUEST_URI'];

 // Splitting it into an array whenever it meets a /
 $tempUri = explode('/', $uri);

 // Removes every value in the array before it meets serversideprogrammering. Also deletes that.
 foreach($tempUri AS $key => $val) {
   unset($tempUri[$key]);
   if($val === 'serversideprogrammering') {
     break;
   }
 }

 $uri = array_values($tempUri);

 // Request the headers
 $headers = apache_request_headers();
 $accept = str_replace(' ', '',$headers['Accept']);
 $accept = explode(',',$accept);
 $userN = $headers['User'];
 $passW = $headers['Pass'];


 // Execute code based on method
 switch ($httpMethod) {
   case 'GET':

   // Looking at the URI for which resources we're asking for
     if($uri[0] === 'products') { // If its products it'll run this following code
       // Creates an instance of the DbCon class
       $dbCon = new DbCon();
       $dbCon->validateLogin($userN, $passW);
       // Get the return value from the getAllProducts function
       $products = $dbCon->getAllProducts();

       // If its json being requested it'll format it as json.
       if(in_array('application/json',$accept)){
           header('Access_Control-Allow-Origin: *');
           header("Content-Type: application/json; charset=utf-8");
           echo json_encode($products);
       } else {
         http_response_code(412);
         die('412 - Wrong accept type. Only JSON supported!');
       }
     } else if ($uri[0] === 'product' && $uri[1] === 'id' && !empty($uri[2])) {
       // Creates an instance of the DbCon class
       $dbCon = new DbCon();

       // Get the return value from the getAllProducts function
       $product = $dbCon->getProductById($uri[2]);
       // Checks if the accept type is json. If it is it'll encode the product as JSON.
       if(in_array('application/json',$accept)){
         header('Access_Control-Allow-Origin: *');
         header("Content-Type: application/json; charset=utf-8");
         echo json_encode($product);
       } else {
         http_response_code(412);
         die('412 - Wrong accept type. Only JSON supported!');
       }
     } else if ($uri[0] === 'product' && $uri[1] === 'name' && !empty($uri[2])) {
       $dbCon = new DbCon();
       $dbCon->checkSecureCon();

       $product = $dbCon->getProductByName($uri[2]);

       if(in_array('application/json',$accept)){
         header('Access_Control-Allow-Origen: *');
         header("Content-Type: application/json; charset=utf-8");
         echo json_encode($product);
       } else {
         http_response_code(412);
         die('412 - Wrong accept type. Only JSON supported!');
       }
     } else {
       http_response_code(404);
       die('404 - Page not found!');
     }
     break;
   case 'POST':
     if($uri[0] === 'products') {
       $dbCon = new DbCon();
       $name = $_POST['name'];
       $desc = $_POST['description'];
       $price = $_POST['price'];

       $dbCon->createProduct($name, $desc, $price);
       $product = $dbCon->getProductById(mysqli_insert_id($dbCon->con));
       echo json_encode($product);
     } else if ($uri[0] === 'product'){
       http_response_code(405);
       die('405 - Method not allowed!');
     } else if ($uri[0] === 'stocks') {
       $dbCon = new DbCon();

     } else {
       http_response_code(404);
       die('404 - Page not found!');
     }
     break;

   case 'PUT':
     if($uri[0] === 'products') {
       $dbCon = new DbCon();

       if(in_array('application/json',$accept)) {
         $result = json_decode(file_get_contents('php://input'));
         $product = $result;

         $productName = $dbCon->updateProduct($product);
         $thisProduct = $dbCon->getProductByName($productName);
         echo json_encode($thisProduct);
       } else {
         echo "Something went wrong with the PUT";
       }
     } else if ($uri[0] === 'products') {
       http_response_code(405);
       die('405 - Method not allowed!');
     } else {
       http_response_code(404);
       die('404 - Page not found!');
     }
     break;

   case 'DELETE':
     if($uri[0] === 'products' && !empty($uri[1])) {
       $dbCon = new DbCon();

       $dbCon->deleteProduct($uri[1]);
     } else if ($uri[0] === 'product'){
       http_response_code(405);
       die('405 - Method not allowed!');
     } else {
       http_response_code(404);
       die('404 - Page not found!');
     }
     break;
   default:
     http_response_code(405);
     die('405 - Bad request method!');
 }
 ?>
