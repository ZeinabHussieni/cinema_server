<?php
require_once("../connection/connection.php");
require_once("../models/Ticket.php");

if (!isset($_GET['user_id'])) {
    echo json_encode(["success" => false, "message" => "Missing user_id"]);
    exit;
}

$userId = (int) $_GET['user_id'];
$tickets = Ticket::getUsertickets($mysqli, $userId);
echo json_encode(["success" => true, "tickets" => $tickets]);
