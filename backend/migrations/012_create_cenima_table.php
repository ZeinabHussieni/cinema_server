<?php
require("../connection/connection.php");

$query ="Create TABLE showtime_snacks (
  showtime_id INT,
  snack_id INT,
  PRIMARY KEY (showtime_id, snack_id),
  FOREIGN KEY (showtime_id) REFERENCES showtimes(id) ON DELETE CASCADE,
  FOREIGN KEY (snack_id) REFERENCES snack_menu(id) ON DELETE CASCADE)";

$execute = $mysqli->prepare($query);

if ($execute->execute()) {
    echo "Table 'showtime_snacks' created successfully.";
} else {
    echo "Error creating table: " . $mysqli->error;
}
?>