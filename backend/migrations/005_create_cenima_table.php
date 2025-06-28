<?php 
require("../connection/connection.php");


$query = "CREATE TABLE ratings(
          id INT AUTO_INCREMENT PRIMARY KEY,
          movie_id INT NOT NULL,
          rating FLOAT CHECK (rating >= 0 AND rating <= 5),
          FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE


)";
$execute = $mysqli->prepare($query);
$execute->execute();