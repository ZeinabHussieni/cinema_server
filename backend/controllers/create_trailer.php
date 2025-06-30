<?php
require("../connection/connection.php");
require_once("../models/Trailer.php");;

// to take the json body from axios
$data = json_decode(file_get_contents("php://input"), true);
   
$movie_id = $data['movie_id'] ?? null;
$trailer_url = $data['trailer_url'] ?? null;


if (!$movie_id || !$trailer_url) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing or invalid required parameters.'
    ]);
    exit;
}
// add movie
$addmovie = Trailer::createtrialers($mysqli, $movie_id, $trailer_url);

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