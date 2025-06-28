<?php
require("../connection/connection.php");

try {
    $mysqli->query("
        INSERT INTO movies (id, title, description, status, release_date, poster_url, created_at) VALUES
        (1, 'Barbie', 'Life in plastic is fantastic.', 'Current', '2024-08-01', 'https://mir-s3-cdn-cf.behance.net/project_modules/hd/ce578c174088799.649bb208e091f.png', NOW()),
        (2, 'Oppenheimer', 'A physicist faces the atomic bomb.', 'Current', '2024-07-15', 'https://www.moviepostersgallery.com/wp-content/uploads/2023/06/Oppenheimer3.jpg', NOW()),
        (3, 'Dune Part 2', 'The saga continues.', 'Upcoming', '2024-10-01', 'https://image.tmdb.org/t/p/original/czembW0Rk1Ke7lCJGahbOhdCuhV.jpg', NOW()),
        (4, 'The Batman', 'A new dark knight rises in Gotham.', 'Current', '2023-03-04', 'https://images6.fanpop.com/image/photos/44100000/The-Batman-2022-Movie-Poster-the-batman-movie-44192645-1370-2048.jpg', NOW()),
        (5, 'Wonka', 'Discover the origin of Willy Wonka.', 'Upcoming', '2024-12-10', 'https://static1.srcdn.com/wordpress/wp-content/uploads/2023/10/wonka-movie-poster.jpg', NOW()),
        (6, 'Inside Out 2', 'Emotions get an upgrade in Riley’s teen years.', 'Upcoming', '2024-11-20', 'http://rotoscopers.com/wp-content/uploads/2015/03/inside-out-final-poster-pixar.jpg', NOW()),
        (7, 'Mission', 'Ethan Hunt is back with more impossible missions.', 'Current', '2024-06-10', 'http://cdn.collider.com/wp-content/uploads/2018/05/mission-impossible-6-poster.jpg', NOW()),
        (8, 'Spider-Man', 'Miles Morales swings across the multiverse.', 'Current', '2024-05-20', 'http://cdn.collider.com/wp-content/uploads/amazing-spider-man-movie-poster.jpg', NOW()),
        (9, 'Avatar', 'Jake Sully lives with his new family on Pandora.', 'Current', '2024-01-15', 'https://static1.colliderimages.com/wordpress/wp-content/uploads/2022/12/coral_1sht_digital_4dx_srgb_v2.jpg', NOW()),
        (10, 'The Marvels', 'Three heroes collide in an epic cosmic team-up.', 'Upcoming', '2024-09-05', 'https://www.themoviedb.org/t/p/original/pbeK8zvKq2h1FGHkgC8ADUnk9uR.jpg', NOW()),
        (11, 'Napoleon', 'The rise and fall of Napoleon Bonaparte.', 'Upcoming', '2024-08-12', 'https://mlpnk72yciwc.i.optimole.com/cqhiHLc.IIZS~2ef73/w:auto/h:auto/q:75/https://bleedingcool.com/wp-content/uploads/2023/10/napoleon_ver5_xlg.jpg', NOW())
    ");


    // seeds for trailers
    $mysqli->query("
        INSERT INTO trailers (movie_id, trailer_url) VALUES
        (1, 'https://www.youtube.com/watch?v=pBk4NYhWNMM'),
        (2, 'https://www.youtube.com/watch?v=uYPbbksJxIg'),
        (3, 'https://www.youtube.com/watch?v=n9xhJrPXop4'),
        (4, 'https://www.youtube.com/watch?v=mqqft2x_Aa4'),
        (5, 'https://www.youtube.com/watch?v=otNh9bTjXWg'),
        (6, 'https://www.youtube.com/watch?v=LEjhY15eCx0'),
        (7, 'https://www.youtube.com/watch?v=fsQgc9pCyDU'),
        (8, 'https://www.youtube.com/watch?v=t06RUxPbp_c'),
        (9, 'https://www.youtube.com/watch?v=d9MyW72ELq0'),
        (10, 'https://www.youtube.com/watch?v=wS_qbDztgVY'),
        (11, 'https://www.youtube.com/watch?v=OAZWXUkrjPc')
    ");

    // seeds for cast
    $mysqli->query("
        INSERT INTO movie_cast (movie_id, actor_name, role_name) VALUES
        (1, 'Margot Robbie', 'Barbie'),
        (2, 'Cillian Murphy', 'Oppenheimer'),
        (3, 'Timothée Chalamet', 'Paul'),
        (4, 'Robert Pattinson', 'Batman'),
        (5, 'Timothée Chalamet', 'Willy Wonka'),
        (6, 'Amy Poehler', 'Joy'),
        (7, 'Tom Cruise', 'Ethan Hunt'),
        (8, 'Shameik Moore', 'Miles Morales'),
        (9, 'Sam Worthington', 'Jake Sully'),
        (10, 'Brie Larson', 'Carol Danvers'),
        (11, 'Joaquin Phoenix', 'Napoleon Bonaparte')
    ");

    // seeds for ratings
    $mysqli->query("
        INSERT INTO ratings (movie_id, rating) VALUES
        (1, 4),
        (2, 4),
        (3, 3),
        (4, 4),
        (5, 4),
        (6, 5),
        (7, 4),
        (8, 5),
        (9, 4),
        (10, 3),
        (11, 4)
    ");

    echo "Seeded successfull";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
