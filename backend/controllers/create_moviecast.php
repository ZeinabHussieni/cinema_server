<?php
require("../connection/connection.php");
require_once("../models/Movie_cast.php");;

// to take the json body from axios
$data = json_decode(file_get_contents("php://input"), true);
   
$movie_id = $data['movie_id'] ?? null;
$actor_name = $data['actor_name'] ?? null;
$role_name = $data['role_name'] ?? null;

if (!$movie_id || !$actor_name||!$role_name) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing or invalid required parameters.'
    ]);
    exit;
}
// add movie
$addmovie = Movie_cast::createmoviecast($mysqli,$movie_id, $actor_name, $role_name);

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