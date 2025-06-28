<?php 
require("../connection/connection.php");


$query = "CREATE TABLE trailers(
          id INT AUTO_INCREMENT PRIMARY KEY,
          movie_id INT NOT NULL,
          trailer_url VARCHAR(500) NOT NULL,
          FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE


)";
$execute = $mysqli->prepare($query);
$execute->execute();