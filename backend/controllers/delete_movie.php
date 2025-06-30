<?php 
require("../models/Movie.php");
require("../connection/connection.php");

$response = [];

if(isset($_GET["id"])){
 $id =$_GET["id"];
  $movie = Movie::MovieDetailsById($mysqli, $id);
 if($movie !== null){
    $movie = Movie::DeleteById($mysqli, $id);
  }else{
    $response["message"]="No movie found";
    }
}
echo json_encode($response);