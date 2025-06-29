<?php
require_once("Model.php");

class Showtime extends Model {
    private $id;
    private $movie_id;
    private $show_datetime;  
    private  $capacity;
    private $created_at;
    private $ticket_price;
    protected static string $table = "showtimes";
    public function __construct(array $data) {
        $this->id = $data["id"];
        $this->movie_id = $data["movie_id"];
        $this->show_datetime = $data["show_datetime"];
        $this->capacity = $data["capacity"];
        $this->created_at = $data["created_at"];
        $this->ticket_price = isset($data["ticket_price"]) ? (float)$data["ticket_price"] : 10.00; // default fallback
   
    }
    public function getId(): int {
        return $this->id;
    }

    public function getMovie_id(): int {
        return $this->movie_id;
    }
    public function getShow_datetime(): string {
        return $this->show_datetime;
    }

    public function getCapacity(): int {
        return $this->capacity;
    }

    public function getCreated_at(): string {
        return $this->created_at;
    }
    public function getTicketPrice(): float {
        return $this->ticket_price;
    }
    public function setMovie_id(int $movie_id): void {
        $this->movie_id = $movie_id;
    }

    public function setShow_datetime(string $show_datetime): void {
        $this->show_datetime = $show_datetime;
    }

    public function setCapacity(int $capacity): void {
        $this->capacity = $capacity;
    }
    public function setTicketPrice(float $price): void {
        $this->ticket_price = $price;
    }

    public function toArray(): array {
        return [
            $this->id,$this->movie_id,$this->show_datetime,$this->capacity,$this->created_at,$this->ticket_price];}


    //get showtime id for specific movie
   public static function getshowtimeId(mysqli $mysqli, int $movieId): ?array {
        $sql = "Select id, show_datetime, capacity, ticket_price FROM showtimes WHERE movie_id = ?";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $mysqli->error);
            return null;
        }

        $stmt->bind_param("i", $movieId);
        $stmt->execute();

        $result = $stmt->get_result();
        $showtimes = [];
        while ($row = $result->fetch_assoc()) {
            $showtimes[] = $row;
        }
        return $showtimes;
   }
       //fetch ticket price for specific showtime
   public static function getPrice(mysqli $mysqli, int $showtimeId): ?float {
    $sql = "Select ticket_price FROM showtimes WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $showtimeId);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();
    return $result ? (float)$result["ticket_price"] : null;
}


}
