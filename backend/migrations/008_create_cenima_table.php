<?php
require("../connection/connection.php");

$query =" ALTER TABLE tickets
  ADD COLUMN payment_status ENUM('paid', 'pending', 'failed') DEFAULT 'pending',
  ADD COLUMN price DECIMAL(6,2) NOT NULL";
$execute = $mysqli->prepare($query);
$execute->execute();

?>
