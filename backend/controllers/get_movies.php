<?php 
require("../models/Movie.php");
require("../connection/connection.php");

$response = [];

if(isset($_GET["id"])){
 $id =$_GET["id"];
 $movie = Movie::MovieDetailsById($mysqli, $id);
 if($movie !== null){
   $response["movie"]=$movie;
  }else{
    $response["message"]="No movie found";
    }
}else {
  $movies = Movie::MoviesDetails($mysqli);
  if (!empty($movies)) {
    $response["movies"] = $movies;
  } else {
    $response["message"] = "No movies found";
  }
}
echo json_encode($response);