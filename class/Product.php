<?php
  class Product {
    // Attributes
    private $id;
    private $name;
    private $description;
    private $price;

    function __construct($id, $name, $description, $price) {
      $this->id = $id;
      $this->name = $name;
      $this->description = $description;
      $this->price = $price;
    }

    public function getId() {
      return $this->id;
    }

    public function getName() {
      return $this->name;
    }

    public function getDesc() {
      return $this->description;
    }

    public function getPrice() {
      return $this->price;
    }
  }
?>
