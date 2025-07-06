<?php

require_once __DIR__ . '/../models/Movie_cast.php';
require_once __DIR__ . '/../services/Movie_CastService.php';
require_once __DIR__ . '/BaseController.php';

class Movie_CastController extends BaseController
{
   
      public function createMovieCast()
    {
        try {
            
            $input = json_decode(file_get_contents('php://input'), true);

       
            $required = ["movie_id", "actor_name", "role_name"];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    return $this->error("Missing field: $field", 400);
                }
            }


            $data = [
                "movie_id"    => $input["movie_id"],
                "actor_name"  => $input["actor_name"],
                "role_name"   => $input["role_name"],
            ];

  
            $inserted = Movie_cast::create($this->mysqli, $data);

            if ($inserted) {
                return $this->respondSuccess([
                    "success" => true,
                    "message" => "Movie cast added successfully"
                ]);
            } else {
                return $this->error("Movie cast creation failed", 500);
            }

        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
