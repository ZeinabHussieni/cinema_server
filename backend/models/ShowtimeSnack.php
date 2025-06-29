 <?php
require_once("Model.php");
class ShowtimeSnack extends Model {
    private  $showtime_id;
    private  $snack_id;

    protected static string $table = "showtime_snacks";

    public function __construct(array $data) {
        $this->showtime_id = $data["showtime_id"];
        $this->snack_id = $data["snack_id"];
    }

    public function getShowtimeId(): int {
        return $this->showtime_id;
    }
    public function getSnackId(): int {
        return $this->snack_id;
    }

    public function toArray(): array {
        return [$this->showtime_id, $this->snack_id];
    }
}