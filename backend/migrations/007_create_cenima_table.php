<?php
require("../connection/connection.php");

$query = "Create TABLE tickets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  showtime_id INT NOT NULL,
  quantity INT DEFAULT 1,
  seat_number INT NULL,
  purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (showtime_id) REFERENCES showtimes(id) ON DELETE CASCADE
)";

$execute = $mysqli->prepare($query);
$execute->execute();
?>
