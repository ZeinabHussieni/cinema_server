<?php 
require("../connection/connection.php");


$query = "CREATE TABLE movies(
           id INT AUTO_INCREMENT PRIMARY KEY,
           title VARCHAR(225)NOT NULL,
           description VARCHAR(225),
           status ENUM('Current','Upcoming')NOT NULL,
           release_date DATE,
           poster_url VARCHAR(500),
           created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP                 
)";
$execute = $mysqli->prepare($query);
$execute->execute();