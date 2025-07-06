<?php

require_once __DIR__ . '/../models/Movie.php';
require_once __DIR__ . '/../services/MovieService.php';
require_once __DIR__ . '/BaseController.php';

class MovieController extends BaseController
{
    public function createMovie()
    {
        try {
            //to take json body decode it
            $input = json_decode(file_get_contents('php://input'), true);

            $required = ["title", "description", "status", "release_date", "poster_url", "created_at"];

            foreach ($required as $field) {
                if (empty($input[$field])) {
                    return $this->error("Missing field: $field", 400);
                }
            }

            $data = [
                "title"        => $input["title"],
                "description"  => $input["description"],
                "status"       => $input["status"],
                "release_date" => $input["release_date"],
                "poster_url"   => $input["poster_url"],
                "created_at"   => $input["created_at"],
            ];

            $inserted = Movie::create($this->mysqli, $data);

            if ($inserted) {
                $movie_id = $this->mysqli->insert_id;
                return $this->respondSuccess([
                    "success"  => true,
                    "message"  => "Movie created successfully",
                    "movie_id" => $movie_id,
                ]);
            } else {
                return $this->error("Movie creation failed", 500);
            }
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function DeleteMovie()
    {
        if (isset($_GET["id"])) {
            $id = (int)$_GET["id"];

            try {
                $success = Movie::delete($this->mysqli, $id);
                $this->respondSuccess($success);
            } catch (Exception $e) {
                $this->error($e->getMessage(), 500);
            }
        }
    }

    public function getMovie()
    {
        try {
            if (isset($_GET["id"])) {
                $id = $_GET["id"];
                $movie = Movie::MovieDetailsById($this->mysqli, $id);

                if (!$movie) {
                    return $this->error("Movie not found", 404);
                }

                return $this->respondSuccess(['movie' => $movie]);
            } else {
                return $this->error("Movie ID is required", 400);
            }
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function getAllMovies()
    {
        try {
            $movies = Movie::MoviesDetails($this->mysqli);

            if (!$movies || count($movies) === 0) {
                return $this->error("No movies found", 404);
            }

            return $this->respondSuccess(['movies' => $movies]);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
