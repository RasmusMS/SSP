<?php
include_once('Product.php');

class DbCon{

    // Attributes
    private $host = 'localhost';
    private $user = 'root';
    private $db = 'bs_webshop';
    private $pw = 'root';
    private $conn;

    // Constructor method
    function __construct(){
        // Instantier MYSQLI objekt til datahåndtering
        $this->conn = new mysqli($this->host, $this->user,$this->pw, $this->db);
        
        // test om forbindelsen virker ellers luk ned!
        if($this->conn->connect_errno != ''){
            echo '<h1>'.$this->conn->connect_error.'</h1>';
            die('Der er ikke forbindelse til databasen!');
        } else {
            // Sæt charset til UTF8, hvis forbindelsen virker
            $this->conn->set_charset('UTF8');
        }
    }

    // Hente alle produkter og returnere et array af produkt objekter
    public function getAllProducts(){
        $products = array();

        $sql = 'SELECT idproduct, product.name, product.description, price FROM product';

        $result = $this->conn->query($sql);

        while($row = $result->fetch_object()){
            //echo $row->name.'<br>';
            $products[] = new Product($row->idproduct, $row->name, $row->description, $row->price);
        }


        return $products;

    }

    public function getProductById($id){
        $product = null;

        $sql = "SELECT idproduct, product.name, product.description, price FROM product WHERE idproduct = $id";

        $result = $this->conn->query($sql);

        while($row = $result->fetch_object()){
            $product = new Product($row->idproduct, $row->name, $row->description, $row->price);
        }


        return $product;

    }





}