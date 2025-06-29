<?php
require_once("Model.php");
class SnackOrder extends Model {
    private $id;
    private $user_id;
    private $ticket_id;
    private $movie_id;
    private $showtime_id;
    private $seat_number;
    private $snack_id;
    private $quantity;
    private $price;
    private $order_time;
    private $delivery_status;

    protected static string $table = "snack_orders";

    public function __construct(array $data) {
        $this->id = $data["id"];
        $this->user_id = $data["user_id"];
        $this->ticket_id = $data["ticket_id"] ?? null;
        $this->movie_id = $data["movie_id"];
        $this->showtime_id = $data["showtime_id"];
        $this->seat_number = $data["seat_number"] ?? null;
        $this->snack_id = $data["snack_id"];
        $this->quantity = $data["quantity"];
        $this->price = (float)$data["price"];
        $this->order_time = $data["order_time"];
        $this->delivery_status = $data["delivery_status"];
    }

    public function getId(): int {
        return $this->id;
    }
    public function getUserId(): int {
        return $this->user_id;
    }
    public function getTicketId(): ?int {
        return $this->ticket_id;
    }
    public function getMovieId(): int {
        return $this->movie_id;
    }
    public function getShowtimeId(): int {
        return $this->showtime_id;
    }
    public function getSeatNumber(): ?string {
        return $this->seat_number;
    }
    public function getSnackId(): int {
        return $this->snack_id;
    }
    public function getQuantity(): int {
        return $this->quantity;
    }
    public function getPrice(): float {
        return $this->price;
    }
    public function getOrderTime(): string {
        return $this->order_time;
    }
    public function getDeliveryStatus(): string {
        return $this->delivery_status;
    }

    public function setDeliveryStatus(string $status) {
        $this->delivery_status = $status;
    }

    public function toArray(): array {
        return [$this->id,$this->user_id,$this->ticket_id,$this->movie_id,$this->showtime_id,$this->seat_number,$this->snack_id,$this->quantity,
            $this->price,$this->order_time,$this->delivery_status,];
    }

    public static function getSnacksShowtime(mysqli $mysqli, int $showtimeId): array {
    $sql = "Select sm.id, sm.snack_name, sm.price FROM showtime_snacks ss
            JOIN snack_menu sm ON ss.snack_id = sm.id WHERE ss.showtime_id = ?";
    
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $showtimeId);
    $stmt->execute();

    $result = $stmt->get_result();
    $snacks = [];
    while ($row = $result->fetch_assoc()) {
        $snacks[] = $row;
    }
    return $snacks;
}
  public static function insertSnackOrder(mysqli $mysqli, array $data): bool {
    $query = "Insert INTO snack_orders (user_id, ticket_id, movie_id, showtime_id, seat_number, snack_id, quantity, price, order_time, delivery_status)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, DEFAULT, DEFAULT)";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $mysqli->error);
        return false;
    }
    //here we used data cause we are getting data from assoc array like get me this index from this array
    $stmt->bind_param(
      "iiiisidd",$data['user_id'],$data['ticket_id'],$data['movie_id'],$data['showtime_id'],
      $data['seat_number'],$data['snack_id'],$data['quantity'],$data['price']);

    return $stmt->execute();
}

  public static function getSnackNameById(mysqli $mysqli, int $snackId): string {
    $sql = "Select snack_name FROM snack_menu WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $snackId);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    return $res ? $res["snack_name"] : "Unknown";
}

}
