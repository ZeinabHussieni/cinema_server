<?php
require_once("../connection/connection.php");
require_once("../models/SnackOrder.php");

if (!isset($_GET['showtime_id'])) {
    echo json_encode(["success" => false, "message" => "Missing showtime_id"]);
    exit;
}

$showtimeId = (int) $_GET['showtime_id'];
$snacks = SnackOrder::getSnacksShowtime($mysqli, $showtimeId);
echo json_encode(["success" => true, "snacks" => $snacks]);
