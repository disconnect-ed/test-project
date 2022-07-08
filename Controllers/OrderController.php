<?php

class OrderController {

    private $conn;
    private $table_name = "orders";

    public $pizza_title;
    public $pizza_size;
    public $pizza_cost;
    public $pizza_cost_BYN;
    public $sauce_title;
    public $sauce_cost;
    public $sauce_cost_BYN;
    public $total_cost;
    public $total_cost_BYN;
    public $order_date;

    public function __construct($db){
        $this->conn = $db;
    }

    public function createOrder() {
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    pizza_title=:pizza_title, pizza_size=:pizza_size, pizza_cost=:pizza_cost, order_date=:order_date,
                    pizza_cost_BYN=:pizza_cost_BYN, sauce_cost=:sauce_cost, sauce_cost_BYN=:sauce_cost_BYN,
                    sauce_title=:sauce_title, total_cost=:total_cost, total_cost_BYN=:total_cost_BYN";

        $stmt = $this->conn->prepare($query);

        $this->pizza_title=htmlspecialchars(strip_tags($this->pizza_title));
        $this->pizza_size=htmlspecialchars(strip_tags($this->pizza_size));
        $this->pizza_cost=htmlspecialchars(strip_tags($this->pizza_cost));
        $this->pizza_cost_BYN && $this->pizza_cost_BYN=htmlspecialchars(strip_tags($this->pizza_cost_BYN));
        $this->sauce_title && $this->sauce_title=htmlspecialchars(strip_tags($this->sauce_title));
        $this->sauce_cost && $this->sauce_cost=htmlspecialchars(strip_tags($this->sauce_cost));
        $this->sauce_cost_BYN && $this->sauce_cost_BYN=htmlspecialchars(strip_tags($this->sauce_cost_BYN));
        $this->total_cost=htmlspecialchars(strip_tags($this->total_cost));
        $this->total_cost_BYN && $this->total_cost_BYN=htmlspecialchars(strip_tags($this->total_cost_BYN));

        $stmt->bindParam(":pizza_title", $this->pizza_title);
        $stmt->bindParam(":pizza_size", $this->pizza_size);
        $stmt->bindParam(":pizza_cost", $this->pizza_cost);
        $stmt->bindParam(":pizza_cost_BYN", $this->pizza_cost_BYN);
        $stmt->bindParam(":sauce_title", $this->sauce_title);
        $stmt->bindParam(":sauce_cost", $this->sauce_cost);
        $stmt->bindParam(":sauce_cost_BYN", $this->sauce_cost_BYN);
        $stmt->bindParam(":total_cost", $this->total_cost);
        $stmt->bindParam(":total_cost_BYN", $this->total_cost_BYN);
        $stmt->bindParam(":order_date", $this->order_date);

        if($stmt->execute()){
            return true;
        }

        return false;
    }

}
