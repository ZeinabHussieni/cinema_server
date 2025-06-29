<?php
require("../connection/connection.php");
require_once("../models/Showtime.php");  


if (!isset($_GET['movie_id'])) {
    echo json_encode(["error" => "movie_id parameter is required"]);
    exit;
}
$movieId = (int)$_GET['movie_id'];

$showtimes = Showtime::getshowtimeId($mysqli, $movieId);

if ($showtimes !== null) {
    echo json_encode(["showtimes" => $showtimes]);
} else {
    echo json_encode(["showtimes" => []]);
}
