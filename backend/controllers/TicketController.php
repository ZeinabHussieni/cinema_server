<?php

require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../services/TicketService.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Showtime.php'; 

class TicketController extends BaseController
{
   public function BuyTicket(){

      try{
         $input = json_decode(file_get_contents("php://input"), true);

         $required = ["user_id", "movie_id", "showtime_id", "seat_numbers"];

         foreach($required as $field){
              if(empty($input[$field])){
                return $this->error("Missing field: $field", 400);
              }
            }

           
            $userId = (int)$input["user_id"];
            $movieId = (int)$input["movie_id"];
            $showtimeId = (int)$input["showtime_id"];
            $seatNumbers = $input["seat_numbers"];
            $checkingUserTickets= Ticket::canBuyTickets($this->mysqli, $userId, $movieId, count($seatNumbers));

          if(!$checkingUserTickets){
            return $this->error('You have exceeded the maximum allowed tickets for this movie.');
          }

         $ticketPrice = Showtime::getPrice($this->mysqli, $showtimeId);
         $BuyTicketSuccess = Ticket::BuyTickets($this->mysqli, $userId, $showtimeId, $seatNumbers, $ticketPrice);

         if($BuyTicketSuccess){
              
             return $this->respondSuccess([
                     "success" => true,
                     "message" => "Tickets purchased successfully"
                 ]);
            } else {
                   return $this->error("failed", 500);
                }
        

        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
    
   public function getTakenSeats() {
      try {
         $showtimeId = isset($_GET['showtime_id']) ? (int)$_GET['showtime_id'] : null;

         if (!$showtimeId) {
            return $this->respondSuccess(['takenSeats' => []]);//no seats
         }

         $seatsTaken = Ticket::getTakenSeats($this->mysqli, $showtimeId);

         return $this->respondSuccess(['takenSeats' => $seatsTaken]);

        } catch (Exception $e) {
         return $this->error($e->getMessage(), 500);
       }
    }

    public function getUserTickets(){
       try {
          $user_id = $_GET['user_id'] ?? null; 

           if (!$user_id) {
             return $this->error('User ID not provided', 400);
           }

           $usertickets = Ticket::getUsertickets($this->mysqli, $user_id);

           if ($usertickets) {
               return $this->respondSuccess([
                  "success" => true,
                  "message" => "User tickets fetched",
                  "tickets" => $usertickets
               ]);
            } else {
             return $this->error("No tickets found", 404);
            }

        } catch (Exception $e) {
          return $this->error($e->getMessage(), 500);
        }
    }
    

}
