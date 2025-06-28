<?php 
require("../connection/connection.php");


$query = "CREATE TABLE movie_cast(
          id INT AUTO_INCREMENT PRIMARY KEY,
          movie_id INT NOT NULL,
          actor_name VARCHAR(255) NOT NULL,
          role_name VARCHAR(255) NOT NULL,
          FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE


)";
$execute = $mysqli->prepare($query);
$execute->execute();