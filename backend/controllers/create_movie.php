<?php
require("../connection/connection.php");
require_once("../models/Movie.php");;

// to take the json body from axios
$data = json_decode(file_get_contents("php://input"), true);

$title = $data['title'] ?? null;
$description = $data['description'] ?? null;
$status = $data['status'] ?? null;
$poster_url = $data['poster_url'] ?? null;
$release_date = $data['release_date'] ?? null;
$created_at = $data['created_at'] ?? null;
if (!$title || !$description || !$status || !$poster_url || !$created_at ) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing or invalid required parameters.'
    ]);
    exit;
}
// add movie
$addmovie = Movie::createMovie($mysqli, $title, $description, $status, $release_date, $poster_url, $created_at);

if ($addmovie) {
    $movie_id = $mysqli->insert_id;//to allow others to take it
    echo json_encode([
        'success' => true,
        'movie_id' => $movie_id,  
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