<?php
require("../connection/connection.php");
require_once("../models/rating.php");;

// to take the json body from axios
$data = json_decode(file_get_contents("php://input"), true);
   
$movie_id = $data['movie_id'] ?? null;
$rating = $data['rating'] ?? null;


if (!$movie_id || !$rating) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing or invalid required parameters.'
    ]);
    exit;
}
// add movie
$addmovie = Rating::createrating($mysqli, $movie_id, $rating);

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