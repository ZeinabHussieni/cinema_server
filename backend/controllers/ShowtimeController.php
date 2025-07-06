<?php

require_once __DIR__ . '/../models/Showtime.php';
require_once __DIR__ . '/../services/ShowtimeService.php';
require_once __DIR__ . '/BaseController.php';

class ShowtimeController extends BaseController
{

    public function createShowtime()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);

            $required = ["movie_id", "show_datetime", "capacity", "created_at", "ticket_price"];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    return $this->error("Missing field: $field", 400);
                }
            }

            $data = [
                "movie_id"      => $input["movie_id"],
                "show_datetime" => $input["show_datetime"],
                "capacity"      => $input["capacity"],
                "created_at"    => $input["created_at"],
                "ticket_price"  => $input["ticket_price"]
            ];

            $inserted = Showtime::create($this->mysqli, $data);

            if ($inserted) {
                return $this->respondSuccess([
                    "success" => true,
                    "message" => "Showtime added successfully"
                ]);
            } else {
                return $this->error("Showtime creation failed", 500);
            }

        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }


    public function getShowtimeByMovieId()
    {
        try {
            if (!isset($_GET['movie_id'])) {
                return $this->error("movie_id parameter is required", 400);
            }

            $movieId = (int) $_GET['movie_id'];

            $showtimes = Showtime::getshowtimeId($this->mysqli, $movieId);

            return $this->respondSuccess([
                "success"   => true,
                "showtimes" => $showtimes ?? []
            ]);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
