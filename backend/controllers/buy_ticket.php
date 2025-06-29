<?php
require("../connection/connection.php");
require_once("../models/Ticket.php");
require_once("../models/Showtime.php");

// to take the json body from axios
$data = json_decode(file_get_contents("php://input"), true);

$userId = $data['user_id'] ?? null;
$movieId = $data['movie_id'] ?? null;
$showtimeId = $data['showtime_id'] ?? null;
$seatNumbers = $data['seat_numbers'] ?? null;

if (!$userId || !$movieId || !$showtimeId || !$seatNumbers || !is_array($seatNumbers)) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing or invalid required parameters.'
    ]);
    exit;
}

// check if user can buy the tickets
if (!Ticket::canBuyTickets($mysqli, $userId, $movieId, count($seatNumbers))) {
    echo json_encode([
        'success' => false,
        'message' => 'You have exceeded the maximum allowed tickets for this movie.'
    ]);
    exit;
}
$ticketPrice = Showtime::getPrice($mysqli, $showtimeId);
// buy ticket
$purchaseSuccess = Ticket::BuyTickets($mysqli, $userId, $showtimeId, $seatNumbers, $ticketPrice);

if ($purchaseSuccess) {
    echo json_encode([
        'success' => true,
        'message' => 'Tickets purchased successfully!'
    ]);
} else {
    $lastError = error_get_last();
    echo json_encode([
        'success' => false,
        'message' => 'Purchase failed. See server logs for details.',
        'error' => $lastError ? $lastError['message'] : 'Unknown error'
    ]);
}