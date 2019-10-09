<?php
class Product implements JsonSerializable{

    private $id;
    private $name;
    private $description;
    private $price;

    function __construct($id, $name, $description, $price){
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
    }

    public function getName(){
        return $this->name;
    }

    public function jsonSerialize(){
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "description"=>$this->description,
            "price"=>$this->price
            ];
    }
}