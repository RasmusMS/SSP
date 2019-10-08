<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'class/DbCon.php';

// Creates an instance of the DbCon class
$dbCon = new DbCon();

// Get the return value from the getAllProducts function
$products = $dbCon->getAllProducts();

// Loops through each product
foreach($products AS $prod) {
  echo $prod->getName();
  echo '<br>';
};
echo "<h1>Hello World!</h1>";
?>
