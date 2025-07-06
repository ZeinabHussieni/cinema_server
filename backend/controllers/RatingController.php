<?php

require_once __DIR__ . '/../models/Rating.php';
require_once __DIR__ . '/../services/RatingService.php';
require_once __DIR__ . '/BaseController.php';

class RatingController extends BaseController
{

      public function createrating()
    {
        try {
            
            $input = json_decode(file_get_contents('php://input'), true);

          
            $required = ["movie_id", "rating"];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    return $this->error("Missing field: $field", 400);
                }
            }


            $data = [
                "movie_id"    => $input["movie_id"],
                "rating"  => $input["rating"],
            ];

  
            $inserted = Rating::create($this->mysqli, $data);

            if ($inserted) {
                return $this->respondSuccess([
                    "success" => true,
                    "message" => "Rating cast added successfully"
                ]);
            } else {
                return $this->error("Rating creation failed", 500);
            }

        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
