<?php
require_once("Model.php");

class Movie extends Model{
    private $id;
    private $title;
    private $description;
    private $status;
    private $release_date;
    private $poster_url;
    private $created_at;

    protected static string $table = "movies";
    public function __construct(array $data){
        $this->id = $data["id"];
        $this->title = $data["title"];
        $this->description = $data["description"];
        $this->status = $data["status"];
        $this->release_date = $data["release_date"];
        $this->poster_url = $data["poster_url"];
        $this->created_at = $data["created_at"];     
    }
    
    public function getId():int{
        return $this->id;
    }
    public function getTitle(): string{
        return $this->title;
    }
    public function getDescription(): string{
        return $this->description;
    }
    public function getStatus(): string{
        return $this->status;
    }
    public function getRelease_date(): string{
        return $this->release_date;
    }
    public function getPoster_url(): string{
        return $this->poster_url;
    }
    public function getCreated_at(): string{
        return $this->created_at;
    }
    public function setTitle(string $title){
        $this->title=$title;
    }
    public function setDescription(string $description){
        $this->description=$description;
    }
    public function setStatus(string $status){
        $this->status=$status;
    }
    public function setRelease_date(string $release_date){
        $this->release_date=$release_date;
    }
    public function setPoster_url(string $poster_url){
        $this->poster_url=$poster_url;
    }

    public function toArray(){
        return [$this->id, $this->title,$this->description,$this->status,$this->release_date,$this->poster_url,
                $this->created_at];
    }

}      