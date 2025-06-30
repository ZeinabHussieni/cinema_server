<?php
require_once("Model.php");

class Trailer extends Model{
    private $id;
    private $movie_id;
    private $trailer_url;


    protected static string $table = "trailers";
    public function __construct(array $data){
        $this->id = $data["id"];
        $this->movie_id = $data["movie_id"];
        $this->trailer_url = $data["trailer_url"];

    }
    public function getId():int{
        return $this->id;
    }
    public function getMovie_id(): int {
        return $this->movie_id;
    }
    public function getTrailer_url(): string{
        return $this->trailer_url;
    }
  
    public function setTrailer_url(string $trailer_url){
        $this->trailer_url=$trailer_url;
    }

    public function toArray(){
        return [$this->id, $this->movie_id,$this->trailer_url];
    }
public static function createtrialers(mysqli $mysqli,$movie_id,$trailer_url):bool{
        $sql=sprintf("Insert Into %s (movie_id,trailer_url) values 
              (?,?)",static::$table);
        $stmt = $mysqli->prepare($sql);
        if(!$stmt){
            error_log("Insert Failed:".$mysql->error);
            return false;
        }
         $stmt->bind_param("is",$movie_id,$trailer_url);
         $stmt->execute();
         return true;
    }
}      