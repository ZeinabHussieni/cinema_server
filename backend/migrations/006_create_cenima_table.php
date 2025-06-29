<?php
require("../connection/connection.php");

$query = "Create TABLE showtimes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  movie_id INT NOT NULL,
  show_datetime DATETIME NOT NULL,
  capacity INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
)";

$execute = $mysqli->prepare($query);
$execute->execute();
?>
