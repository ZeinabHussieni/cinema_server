<?php 
require("../connection/connection.php");


$query = "CREATE TABLE users(
          id INT(11) AUTO_INCREMENT PRIMARY KEY, 
         email VARCHAR(255) NOT NULL UNIQUE,
         phoneNumber VARCHAR(255) NOT NULL, 
         password VARCHAR(255) NOT NULL,
         favoriteGenres VARCHAR(255),         
         paymentMethod VARCHAR(50),
         communicationPrefs VARCHAR(255)
          )";

$execute = $mysqli->prepare($query);
$execute->execute();