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

public function toArray() {
    return [
        "id" => $this->id,
        "title" => $this->title,
        "description" => $this->description,
        "status" => $this->status,
        "release_date" => $this->release_date,
        "poster_url" => $this->poster_url,
        "created_at" => $this->created_at
    ];
}


    public static function MoviesDetails(mysqli $mysqli):?array{
                                        $sql=("Select m.id,m.title,m.description,m.status,m.release_date,m.poster_url,m.created_at,GROUP_CONCAT(DISTINCT t.trailer_url) as trailers,
                                        GROUP_CONCAT(DISTINCT CONCAT(c.actor_name,'as',c.role_name)) as movie_cast, ROUND(AVG(r.rating),1)AS ratings from movies m
                                        left join trailers t on m.id=t.movie_id
                                        left join movie_cast c on m.id=c.movie_id
                                        left join ratings r on m.id=r.movie_id Group by m.id ");
        $res = $mysqli->query($sql);
        if (!$res) {
        error_log("Prepare failed: " . $mysqli->error);
        return null;
        }
       while ($row = $res->fetch_assoc()) {
        $movies[] = $row;
       }

        return $movies;

}
    public static function MovieDetailsById(mysqli $mysqli,int $id):?array{
                                        $sql=("Select m.id,m.title,m.description,m.status,m.release_date,m.poster_url,m.created_at,GROUP_CONCAT(DISTINCT t.trailer_url) as trailers,
                                        GROUP_CONCAT(DISTINCT CONCAT(c.actor_name,'as',c.role_name)) as movie_cast, ROUND(AVG(r.rating),1)AS ratings from movies m
                                        left join trailers t on m.id=t.movie_id
                                        left join movie_cast c on m.id=c.movie_id
                                        left join ratings r on m.id=r.movie_id where m.id=? Group by m.id ");
        $query = $mysqli->prepare($sql);
        if (!$query) {
        error_log("Prepare failed: " . $mysqli->error);
        return null;
        }
        $query -> bind_param("i",$id);
        $query -> execute();

       $data=$query->get_result()->fetch_assoc();
       return $data ?:null;

                    
    }
}      