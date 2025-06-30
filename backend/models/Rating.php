<?php
require_once("Model.php");

class Rating extends Model{
    private $id;
    private $movie_id;
    private $rating;


    protected static string $table = "ratings";
    public function __construct(array $data){
        $this->id = $data["id"];
        $this->movie_id = $data["movie_id"];
        $this->rating = $data["rating"];

    }
    
    public function getId():int{
        return $this->id;
    }
    public function getMovie_id(): int {
        return $this->movie_id;
    }
    public function getRating(): float {
        return $this->rating;
    }
  
    public function setRating(float  $rating){
        $this->rating=$rating;
    }

    public function toArray(){
        return [$this->id, $this->movie_id,$this->rating];
    }
    public static function createrating(mysqli $mysqli,$movie_id,$rating):bool{
        $sql=sprintf("Insert Into %s (movie_id,rating) values 
              (?,?)",static::$table);
        $stmt = $mysqli->prepare($sql);
        if(!$stmt){
            error_log("Insert Failed:".$mysql->error);
            return false;
        }
         $stmt->bind_param("is",$movie_id,$rating);
         $stmt->execute();
         return true;
    }

}      