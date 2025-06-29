<?php
require("../connection/connection.php");

$showtimeId = $_GET['showtime_id'] ?? null;
if (!$showtimeId) {
    echo json_encode(['takenSeats' => []]);
    exit;
}

$sql = "Select seat_number FROM tickets WHERE showtime_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $showtimeId);
$stmt->execute();
$res = $stmt->get_result();

$takenSeats = [];
while ($row = $res->fetch_assoc()) {
    $takenSeats[] = (int)$row['seat_number'];
}
echo json_encode(['takenSeats' => $takenSeats]);
?>
