<?php
require_once("Model.php");

class Showtime extends Model {
    private int $id;
    private int $movie_id;
    private string $show_datetime;  
    private int $capacity;
    private string $created_at;

    protected static string $table = "showtimes";
    public function __construct(array $data) {
        $this->id = $data["id"];
        $this->movie_id = $data["movie_id"];
        $this->show_datetime = $data["show_datetime"];
        $this->capacity = $data["capacity"];
        $this->created_at = $data["created_at"];
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
    public function setMovie_id(int $movie_id): void {
        $this->movie_id = $movie_id;
    }

    public function setShow_datetime(string $show_datetime): void {
        $this->show_datetime = $show_datetime;
    }

    public function setCapacity(int $capacity): void {
        $this->capacity = $capacity;
    }

    public function toArray(): array {
        return [
            $this->id,$this->movie_id,$this->show_datetime,$this->capacity,$this->created_at];}

    public static function getshowtimeId(mysqli $mysqli, int $movieId): ?array {
     $sql = "Select id, show_datetime, capacity FROM showtimes WHERE movie_id = ?";
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
         $showtimes[] = $row; }
     return $showtimes;
    }



}
