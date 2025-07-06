<?php

require_once __DIR__ . '/../models/SnackOrder.php';
require_once __DIR__ . '/../services/SnackOrderService.php';
require_once __DIR__ . '/BaseController.php';

class SnackOrderController extends BaseController
{
    public function getSnackShowTime(){
       try {
           $showtimeId = isset($_GET['showtime_id']) ? (int)$_GET['showtime_id'] : null;
    
           if (!$showtimeId) {
             return $this->respondSuccess(['message' => 'Showtime Id not found']);
            }

           $getsnacks = SnackOrder::getSnacksShowtime($this->mysqli, $showtimeId);

           return $this->respondSuccess([
               'success' => true,
               'snacks' => $getsnacks
           ]);

        } catch (Exception $e) {
          return $this->error($e->getMessage(), 500);
        }
    }

    public function insertSnack(){
        try {
            $input = json_decode(file_get_contents('php://input'), true);

            $required = ["user_id", "ticket_id", "movie_id", "showtime_id", "seat_number", "snack_id", "quantity", "price"];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    return $this->error("Missing field: $field", 400);
                }
            }

            $data = [
                "user_id" => $input["user_id"],
                "ticket_id"=> $input["ticket_id"],
                "movie_id" => $input["movie_id"],
                "showtime_id"  => $input["showtime_id"],
                "snack_id" => $input["snack_id"],
                "quantity" => $input["quantity"],
                "price" => $input["price"],

            ];

            $inserted = SnackOrder::insertSnack($this->mysqli, $data);

            if ($inserted) {
                return $this->respondSuccess([
                    "success" => true,
                    "message" => "SnackOrder added successfully"
                ]);
            } else {
                return $this->error(" failed", 500);
            }

        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    
    }


}
