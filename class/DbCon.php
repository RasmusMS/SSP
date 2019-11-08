<?php
include_once 'Product.php';

class DbCon {
  // Attributes
  private $host = 'localhost';
  private $user = 'root';
  private $pw = 'root';
  private $db = 'ssp';
  public $con;

  function __construct(){
    // Creates an instance of the MYSQLI object for datahandling
    $this->con = new mysqli($this->host, $this->user, $this->pw, $this->db);

    // Testing if the connection works. If not then shuts it down
    if($this->con->connect_errno != ''){
      echo '<h1>'.$this->con->connect_error.'</h1>';
      die('Der er ikke forbindelse til databasen');
    } else {
      // Setting the charset to UTF8
      $this->con->set_charset('UTF8');
    }
  }

  public function checkSecureCon() {
    if(!empty($_SERVER["https"])) {
      echo "Connection secure!";
    } else {
      die("Connection not secure!");
    }
  }

  // Fetching all products and makes an array of products
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
    $sql = $this->con->prepare("SELECT products.idProduct, products.name, products.description, products.price FROM products WHERE products.idProduct = ?");
    $sql->bind_param("i", $thisId);

    $thisId = $id;

    // Executes the sql statement and returns true/falls
    $sql->execute();
    // Because it only returns true/false, I have to add this extra code to get the content of the query.
    $result = $sql->get_result();
    $sql->close();
    // Puts each product into the products array
    while($row = $result->fetch_object()) {
      $product = new Product($row->idProduct, $row->name, $row->description, $row->price);
    }

    // Returns the products
    return $product;
  }

  public function getProductByName($name) {

    $product = null;

    $sql = $this->con->prepare("SELECT products.idProduct, products.name, products.description, products.price FROM products WHERE products.name = ?");
    $sql->bind_param("s", $thisName);

    $thisName = $name;

    $sql->execute();

    $result = $sql->get_result();
    $sql->close();

    while($row = $result->fetch_object()) {
      $product = new Product($row->idProduct, $row->name, $row->description, $row->price);
    }

    return $product;
  }

  // Function for creating products using POST
  public function createProduct($name, $desc, $price) {

    // Here I make a prepared statement and bind it to some parameters. This is to prevent SQL injection
    $sql = $this->con->prepare("INSERT INTO products (products.name, products.description, products.price) VALUES (?, ?, ?)");
    // ssi stands for string, string, int. It defines what input it should expect in each parameter
    $sql->bind_param("ssi", $thisName, $thisDesc, $thisPrice);

    // Here I define my parameters with the variables I get from where the function is called
    $thisName = $name;
    $thisDesc = $desc;
    $thisPrice = $price;

    // Lastly I execute my sql statement in an if else statement.
    if($sql->execute()) {
      echo "Successfully created the product!";
      $sql->close();
    } else {
      $sql->close();
      http_response_code(400);
      die('400 - Bad Request');
    }
  }

  // This function is used to update the data of a product using PUT
  public function updateProduct($thisProduct) {
    // Preparing my statement
    $sql = $this->con->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE idProduct = ?;");
    $sql->bind_param("ssii", $name, $description, $price, $id);

    // defining my parameters
    $name = $thisProduct->name;
    $description = $thisProduct->description;
    $price = $thisProduct->price;
    $id = $thisProduct->id;

    // Executing the SQL in an if else statement
    if($sql->execute()) {
      return $thisProduct->name;
      $sql->close();
    } else {
      $sql->close();
      http_response_code(400);
      die('400 - Bad Request');
    }
  }

  // This function is called whenever a product is being deleted
  public function deleteProduct($id) {
    // Prepared statement.
    $sql = $this->con->prepare("DELETE FROM products WHERE idProduct = ?");
    $sql->bind_param("i", $thisId);

    // Binding the id to the ID parameter
    $thisId = $id;

    // Executing the SQL
    if($sql->execute()) {
      echo "Successfully deleted product!";
      $sql->close();
    } else {
      $sql->close();
      http_response_code(400);
      die('400 - Bad Request');
    }
  }


  public function validateLogin($userName, $passWord) {
    $sql = $this->con->prepare("SELECT userName, passWord FROM admin WHERE userName = ?");
    $sql->bind_param("s", $userName);
    $sql->execute();
    $result = $sql->get_result();
    $sql->close();
    $rowcount = $result->num_rows;

    if($rowcount > 0) {
      while($row = $result->fetch_assoc()) {
        $hashed_password = $row['passWord'];
        if(!(password_verify($passWord, $hashed_password))){
          die("Brugernavnet og/eller adgangskoden er forkert.");
        }
      }
    } else {
      die("User doesn't exist");
    }
  }

  public function createAdmin($userName, $passWord) {
    $hashed_pass = password_hash("$passWord", PASSWORD_DEFAULT);
    $sql = $this->con->prepare("INSERT INTO admin (userName, passWord) VALUES ( ?, ?)");
    $sql->bind_param("ss", $userName, $hashed_pass);
    $sql->execute();
    $sql->close();
    echo "Admin created";
  }

  function __destruct(){
    $this->con->close();
  }
}

  ?>
