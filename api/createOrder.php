<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../Controllers/OrderController.php';
$database = new Database();
$db = $database->getConnection();
$order = new OrderController($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->pizza_title) && !empty($data->pizza_size) && !empty($data->pizza_cost) &&
    !empty($data->total_cost)
){

    $order->pizza_title = $data->pizza_title;
    $order->pizza_size = $data->pizza_size;
    $order->pizza_cost = $data->pizza_cost;
    $order->pizza_cost_BYN = $data->pizza_cost_BYN ? (float) $data->pizza_cost_BYN : null;
    $order->sauce_title = $data->sauce_title ? $data->sauce_title : null;
    $order->sauce_cost = $data->sauce_cost ? (float) $data->sauce_cost : null;
    $order->sauce_cost_BYN = $data->sauce_cost_BYN ? (float) $data->sauce_cost_BYN : null;
    $order->total_cost = (float) $data->total_cost;
    $order->total_cost_BYN = (float) $data->total_cost_BYN ? $data->total_cost_BYN : null;
    $order->order_date = date('Y-m-d H:i:s');

    if($order->createOrder()){

        http_response_code(201);

        echo json_encode(["message" => "Заказ создан", "currentOrder" => $order]);
    }

    else{

        http_response_code(503);

        echo json_encode(array("message" => "Не удалось создать заказ"));
    }
}

else{

    http_response_code(400);

    echo json_encode(array("message" => "Не удалось создать заказ"));
}