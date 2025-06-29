<?php
require("../connection/connection.php");

$query ="Create TABLE snack_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ticket_id INT,
    movie_id INT NOT NULL,
    showtime_id INT NOT NULL,
    seat_number VARCHAR(10),
    snack_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price FLOAT NOT NULL,
    order_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    delivery_status VARCHAR(50) DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE SET NULL,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (showtime_id) REFERENCES showtimes(id) ON DELETE CASCADE,
    FOREIGN KEY (snack_id) REFERENCES snack_menu(id) ON DELETE CASCADE
);
";
$execute = $mysqli->prepare($query);
$execute->execute();
?>