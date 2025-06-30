<?php
require("../connection/connection.php");
require_once("../models/Showtime.php");;

// to take the json body from axios
$data = json_decode(file_get_contents("php://input"), true);
   
$movie_id = $data['movie_id'] ?? null;
$show_datetime = $data['show_datetime'] ?? null;
$capacity = $data['capacity'] ?? null;
$created_at = $data['created_at'] ?? null;
$ticket_price = $data['ticket_price'] ?? null;

if (!$movie_id || !$show_datetime || !$capacity || !$created_at || !$ticket_price ) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing or invalid required parameters.'
    ]);
    exit;
}
// add movie
$addmovie = Showtime::createshowtime($mysqli, $movie_id, $show_datetime, $capacity, $created_at, $ticket_price);

if ($addmovie) {
    echo json_encode([
        'success' => true,
        'message' => 'Added successfully'
    ]);
} else {
    $lastError = error_get_last();
    echo json_encode([
        'success' => false,
        'message' => 'failed. See server logs for details.',
        'error' => $lastError ? $lastError['message'] : 'Unknown error'
    ]);
}