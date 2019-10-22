<?php
include_once 'Product.php';

class DbCon {
  // Attributes
  private $host = 'localhost';
  private $user = 'root';
  private $pw = 'root';
  private $db = 'ssp';
  private $con;

  function __construct(){
    // Instantier MYSQLI objekt til datahåndtering
    $this->con = new mysqli($this->host, $this->user, $this->pw, $this->db);

    // Tester om forbindelsen virker, hvis ikke så lukker det forbindelsen
    if($this->con->connect_errno != ''){
      echo '<h1>'.$this->con->connect_error.'</h1>';
      die('Der er ikke forbindelse til databasen');
    } else {
      // Sætter charset
      $this->con->set_charset('UTF8');
    }
  }

  // Hente alle produkter pg returnere et array af produkt objekter
  public function getAllProducts() {
    // Creates a new array to contain each product
    $products = array();

    // The SQL statement to get each product
    $sql = 'SELECT products.idProduct, products.name, products.description, products.price FROM products';

    // Queries the statement
    $result = $this->con->query($sql);

    // Puts each product into the products array
    while($row = $result->fetch_object()) {
      $products[] = new Product($row->idProduct, $row->name, $row->description, $row->price);
    }

    // Returns the products
    return $products;
  }

  public function getProductById($id) {
    // Creates a new array to contain each product
    $product = null;

    // The SQL statement to get each product
    $sql = "SELECT products.idProduct, products.name, products.description, products.price FROM products WHERE products.idProduct = $id";

    // Queries the statement
    $result = $this->con->query($sql);

    // Puts each product into the products array
    while($row = $result->fetch_object()) {
      $product = new Product($row->idProduct, $row->name, $row->description, $row->price);
    }

    // Returns the products
    return $product;
  }

  /*public function getProductByName($name) {
    $product = null;
    $sql = $this->con->prepare("SELECT products.idProduct, products.name, products.description, products.price FROM products WHERE products.name = ?");
    $sql->bind_param("s", $name);
    $result = $sql->execute();

    while($row = $result->fetch_object()) {
      $product = new Product($row->idProduct, $row->name, $row->description, $row->price);
    }

    return $product;
  }*/

  public function createProduct($name, $desc, $price) {
    $sql = $this->con->prepare("INSERT INTO products (products.name, products.description, products.price) VALUES (?, ?, ?)");
    $sql->bind_param("ssi", $thisName, $thisDesc, $thisPrice);

    $thisName = $name;
    $thisDesc = $desc;
    $thisPrice = $price;

    if($sql->execute()) {
      echo "Successfully created the product!";
    } else {
      http_response_code(400);
      die('400 - Bad Request');
    }
  }

  public function updateProduct($product) {
    $sql = $this->con->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE idProduct = ?;");
    $sql->bind_param("ssii", $name, $description, $price, $id);

    $name = $product->name;
    $description = $product->description;
    $price = $product->price;
    $id = $product->id;

    if($sql->execute()) {
      echo "Successfully updated the product!";
    } else {
      http_response_code(400);
      die('400 - Bad Request');
    }
  }

  public function deleteProduct($id) {
    $sql = "DELETE FROM products WHERE idProduct = $id";

    if($this->con->query($sql)) {
      echo "Successfully deleted product!";
    } else {
      http_response_code(400);
      die('400 - Bad Request');
    }
  }
}

?>
