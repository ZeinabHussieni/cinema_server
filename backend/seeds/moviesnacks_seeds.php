<?php
require("../connection/connection.php");

$mysqli->query("
  INSERT INTO snack_menu (snack_name, price) VALUES
  ('Popcorn - Small', 3.50),
  ('Popcorn - Medium', 5.00),
  ('Popcorn - Large', 7.00),
  ('Soda - Small', 2.00),
  ('Soda - Medium', 3.00),
  ('Soda - Large', 4.00),
  ('Nachos', 4.50),
  ('Candy', 3.00),
  ('Hot Dog', 4.00),
  ('Ice Cream', 3.50)
");
$mysqli->query("
  INSERT INTO showtime_snacks (showtime_id, snack_id) VALUES
  (1, 1), (1, 2), (1, 3),     
  (2, 1), (2, 2), (2, 3),     
  (3, 1), (3, 2), (3, 3),
  (4, 1), (4, 2), (4, 4),      
  (5, 1), (5, 2), (5, 4),
  (6, 1), (6, 3), (6, 4),
  (7, 2), (7, 3), (7, 4),
  (8, 1), (8, 2), (8, 3),
  (9, 1), (9, 3), (9, 4),
  (10, 2), (10, 3), (10, 4),
  (11, 1), (11, 2), (11, 3),
  (12, 1), (12, 2), (12, 4),
  (13, 1), (13, 3), (13, 4),
  (14, 2), (14, 3), (14, 4),
  (15, 1), (15, 2), (15, 3),
  (16, 1), (16, 2), (16, 4)
");
