<?php
// Vis PHP fejl i browseren
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('class/DbCon.php');

// Hent hvilken HTTP request method, der forespørges på
$httpMethod = $_SERVER['REQUEST_METHOD'];

// Hent Uri til ressourcen
$uri = $_SERVER['REQUEST_URI'];

// Formater URI, slet unødig sti info
$tempUri = explode('/', $uri);
foreach ($tempUri AS $key => $val) {
    unset($tempUri[$key]);
    if ($val === 'ssp_api') {
        break;
    }
}
// Gem uri ressourcer i $uri som array
$uri = array_values($tempUri);

// echo '<pre>';
// var_dump($uri);
// echo '</pre>';

$headers = apache_request_headers();
$accept = str_replace(' ', '', $headers['Accept']);
$accept = explode(',', $accept);


// echo '<pre>';
// var_dump($accept);
// echo '</pre>';

// Håndter de forskellige HTTP requests
switch ($httpMethod){
    case 'GET':
        // Kigger på URI og hvilken ressource bliver efterspurgt
        if($uri[0] === 'products'){ // er det produkter
            $dbCon = new DbCon();
            $products = $dbCon->getAllProducts();
            if(in_array('application/json', $accept)){
                // returner i JSON
                
                header('Access-Control-Allow-Origin: *');
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode($products);

            } else if(in_array('application/xml', $accept)){
                // Returner som XML
                echo 'XML PRODUKTER!';
            } else {
                http_response_code(412);
                die('412 - Wrong Accept type. Only JSON and XML supported!');
            }
        }
        else if ($uri[0] === 'product' && $uri[1] === 'id' && !empty($uri[2]))
            
        break;
    case 'POST':
        
        break;
    case 'PUT':
        
        break;
    case 'DELETE':
        
        break;
    default:
        // Forkert HTTP method! TERMINATE!
        http_response_code(405);
        die('405 - Bad request method!');
}





// foreach($products AS $prod){
//     echo $prod->getName();
//     echo '<br>';
// }
// echo '<h2>Underoverskrift</h2>';


