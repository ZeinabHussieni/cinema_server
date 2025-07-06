<?php

require_once __DIR__ . '/../models/Trailer.php';
require_once __DIR__ . '/../services/TrailerService.php';
require_once __DIR__ . '/BaseController.php';

class TrailerController extends BaseController
{
   
    public function createTrailer()
    {
        try {
            // decode json body from axois
            $input = json_decode(file_get_contents('php://input'), true);

            $required = ["movie_id", "trailer_url"];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    return $this->error("Missing field: $field", 400);
                }
            }

            $data = [
                "movie_id"    => $input["movie_id"],
                "trailer_url" => $input["trailer_url"],
            ];

            $inserted = Trailer::create($this->mysqli, $data);

            if ($inserted) {
                return $this->respondSuccess([
                    "success" => true,
                    "message" => "Trailer added successfully"
                ]);
            } else {
                return $this->error("Trailer creation failed", 500);
            }

        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
