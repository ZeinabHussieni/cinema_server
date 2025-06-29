<?php
require("../connection/connection.php");

$query ="ALTER TABLE showtimes ADD COLUMN ticket_price DECIMAL(6,2) NOT NULL DEFAULT 10.00;";
$execute = $mysqli->prepare($query);
$execute->execute();
?>
