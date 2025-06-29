<?php
require("../connection/connection.php");

$query ="Create TABLE snack_menu (
  id INT AUTO_INCREMENT PRIMARY KEY,
  snack_name VARCHAR(100) NOT NULL,
  price FLOAT NOT NULL)";
$execute = $mysqli->prepare($query);
$execute->execute();
?>