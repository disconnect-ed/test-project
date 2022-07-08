<?php

class PizzaController {

    private $conn;

    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;
    public $modified;

    public function __construct($db){
        $this->conn = $db;

    }

    function getPizza(){

        $query = "SELECT * FROM pizzes";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    function getPizzaSizes(){

        $query = "SELECT * FROM sizes";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    function getPizzaSauces(){

        $query = "SELECT * FROM sauces";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }


}