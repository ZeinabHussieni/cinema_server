<?php
require_once("../connection/connection.php");
require_once("../models/SnackOrder.php");

$data = json_decode(file_get_contents("php://input"), true);

$required = ["user_id", "ticket_id", "movie_id", "showtime_id", "seat_number", "snack_id", "quantity", "price"];

foreach ($required as $key) {
    if (!isset($data[$key])) {
        echo json_encode(["success" => false, "message" => "Missing field: $key"]);
        exit;
    }
}

$success = SnackOrder::insertSnackOrder($mysqli, $data);
if ($success) {
    echo json_encode(["success" => true, "message" => "ordered successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "failed to place snack order"]);
}
