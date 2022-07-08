<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../Controllers/PizzaController.php';
include_once '../Controllers/RateController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["message" => 'Данный HTTP метод не поддерживается']);
    return;
}

$database = new Database();
$db = $database->getConnection();

$product = new PizzaController($db);

$stmt = $product->getPizza();
$num = $stmt->rowCount();

if($num>0){

    $rate = new RateController();
    $currentRate = $rate->getCurrentRate();

    $products_arr = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);

        $product_item = [
            "id" => $id,
            "title" => $title,
            "cost" => $cost,
            "cost_BYN" => $currentRate ? round($currentRate * $cost, 2) : null,
            "image" => $image,
            "text" => $text,
        ];

        array_push($products_arr, $product_item);
    }

    http_response_code(200);

    echo json_encode($products_arr);
}
else{

    http_response_code(404);

    echo json_encode(
        array("message" => "Ни одной пиццы не найдено")
    );
}