<?php
require_once("Model.php");

class SnackMenu extends Model {
    private int $id;
    private string $snack_name;
    private float $price;

    protected static string $table = "snack_menu";

    public function __construct(array $data) {
        $this->id = $data["id"];
        $this->snack_name = $data["snack_name"];
        $this->price = (float)$data["price"];
    }

    public function getId(): int {
        return $this->id;
    }
    public function getSnackName(): string {
        return $this->snack_name;
    }
    public function getPrice(): float {
        return $this->price;
    }
    public function setSnackName(string $name) {
        $this->snack_name = $name;
    }
    public function setPrice(float $price) {
        $this->price = $price;
    }

    public function toArray(): array {
        return [$this->id, $this->snack_name, $this->price];
    }
}
