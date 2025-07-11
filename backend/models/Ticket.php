<?php
require_once("Model.php");

class Ticket extends Model{
    private $id;
    private $user_id;
    private $showtime_id;
    private $quantity;
    private $seat_number;
    private $purchase_date;
    private $price;        
    private $payment_status; 

    protected static string $table = "tickets";
    public function __construct(array $data){
        $this->id = $data["id"];
        $this->user_id = $data["user_id"];
        $this->showtime_id = $data["showtime_id"];
        $this->quantity = $data["quantity"];
        $this->seat_number = $data["seat_number"] ?? null;
        $this->purchase_date = $data["purchase_date"];
        $this->price = $data["price"] ?? 0.0;
        $this->payment_status = $data["payment_status"] ?? 'pending';
    }
    
    public function getId():int{
        return $this->id;
    }
    public function getUser_id(): int {
        return $this->user_id;
    }
    public function getShowtime_id(): int {
        return $this->showtime_id;
    }
    public function getQuantity(): string{
        return $this->quantity;
    }
    public function getSeat_number(): ?int {
        return $this->seat_number;
    }
        public function getPurchase_date(): string{
        return $this->purchase_date;
    }
     public function getPrice(): float {
        return $this->price;
    }
    public function getPaymentStatus(): string {
        return $this->payment_status;
    }
    public function setPaymentStatus(string $status) {
        $this->payment_status = $status;
    }
    public function setPrice(float $price) {
        $this->price = $price;
    }
    public function setQuantity(string $quantity){
        $this->quantity=$quantity;
    }
    public function setSeat_number(int $seat_number) {
        $this->seat_number = $seat_number;
    }

    public function setPurchase_date(string $purchase_date){
        $this->purchase_date=$purchase_date;
    }

    public function toArray(){
        return [$this->id, $this->user_id,$this->showtime_id,$this->quantity,$this->seat_number,$this->purchase_date,$this->price,
            $this->payment_status];}

    public static function BuyTickets(mysqli $mysqli, int $userId, int $showtimeId, array $seatNumbers, float $ticketPrice): bool {
    // get taken seats
    $sql = "Select seat_number FROM tickets WHERE showtime_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $showtimeId);
    $stmt->execute();
    $res = $stmt->get_result();
    $taken = [];
    while ($row = $res->fetch_assoc()) {
        $taken[] = (int)$row['seat_number'];
    }

    // check for taken seats requested
    foreach ($seatNumbers as $seat) {
        if (in_array($seat, $taken)) {
            error_log("Seat $seat already taken.");
            return false;
        }
    }

    $sqlInsert = "Insert INTO tickets (user_id, showtime_id, quantity, seat_number, price, payment_status)
                  VALUES (?, ?, ?, ?, ?, 'paid')";
    $stmtInsert = $mysqli->prepare($sqlInsert);
    if (!$stmtInsert) {
        error_log("Prepare failed: " . $mysqli->error);
        return false;
    }
    $quantity = 1; // Each seat is one ticket
    // Insert each seat
    foreach ($seatNumbers as $seat) {
        $stmtInsert->bind_param("iiiid", $userId, $showtimeId, $quantity, $seat, $ticketPrice);
        if (!$stmtInsert->execute()) {
            error_log("Insert failed for seat $seat: " . $stmtInsert->error);
            return false;
        }
    }

    return true;
}

    //check user if he can buy more tickets
    public static function canBuyTickets(mysqli $mysqli, int $userId, int $movieId, int $newQuantity, int $maxAllowed = 5): bool {
        $sql = "Select IFNULL(SUM(t.quantity), 0) AS total FROM tickets t
                JOIN showtimes s ON t.showtime_id = s.id 
                WHERE t.user_id = ? AND s.movie_id = ?";
        
        $query = $mysqli->prepare($sql);
        if (!$query) {
            error_log("Prepare failed: " . $mysqli->error);
            return false;
        }

        $query->bind_param("ii", $userId, $movieId);
        $query->execute();

        $data = $query->get_result()->fetch_assoc();
        $alreadyBought = $data['total'] ?? 0;

        if (($alreadyBought + $newQuantity) > $maxAllowed) {
            return false;
        }
        return true;
    }

  public static function getUsertickets(mysqli $mysqli, int $userId): array {
    $sql = "Select t.id AS ticket_id,t.seat_number,t.showtime_id,t.quantity,s.show_datetime,
            m.title AS movie_title,m.id AS movie_id FROM tickets t JOIN showtimes s ON t.showtime_id = s.id JOIN movies m ON s.movie_id = m.id
            WHERE t.user_id = ?";
    
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $tickets = [];
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
    return $tickets;
}

}      