<?php
require_once("Model.php");

class Movie_cast extends Model{
    private $id;
    private $movie_id;
    private $actor_name;
    private $role_name;

    protected static string $table = "movie_cast";
    public function __construct(array $data){
        $this->id = $data["id"];
        $this->movie_id = $data["movie_id"];
        $this->actor_name = $data["actor_name"];
        $this->role_name = $data["role_name"];
    }
    
    public function getId():int{
        return $this->id;
    }
    public function getMovie_id(): int {
        return $this->movie_id;
    }
    public function getActor_name(): string{
        return $this->actor_name;
    }
        public function getRole_name(): string{
        return $this->role_name;
    }
  
    public function setActor_name(string $actor_name){
        $this->actor_name=$actor_name;
    }
    public function setRole_name(string $role_name){
        $this->role_name=$role_name;
    }

    public function toArray(){
        return [$this->id, $this->movie_id,$this->actor_name,$this->role_name];
    }
      public static function createmoviecast(mysqli $mysqli,$movie_id,$actor_name,$role_name):bool{
        $sql=sprintf("Insert Into %s (movie_id,actor_name,role_name) values 
              (?,?,?)",static::$table);
        $stmt = $mysqli->prepare($sql);
        if(!$stmt){
            error_log("Insert Failed:".$mysql->error);
            return false;
        }
         $stmt->bind_param("iss",$movie_id,$actor_name,$role_name);
         $stmt->execute();
         return true;
    }

}      