<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../Controllers/PizzaController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["message" => 'Данный HTTP метод не поддерживается']);
    return;
}

$database = new Database();
$db = $database->getConnection();

$product = new PizzaController($db);

$stmt = $product->getPizzaSizes();
$num = $stmt->rowCount();

if($num>0){

    $products_arr = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);

        $product_item = [
            "id" => $id,
            "size" => $size,
            "coefficient" => $coefficient,
        ];

        array_push($products_arr, $product_item);
    }

    http_response_code(200);

    echo json_encode($products_arr);
}
else{

    http_response_code(404);

    echo json_encode(
        array("message" => "Ни одного размера не найдено")
    );
}