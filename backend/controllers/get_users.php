<?php 
require("../models/User.php");
require("../connection/connection.php");

$response = [];

if(isset($_GET["id"])){
 $id =$_GET["id"];
 $user = User::find($mysqli, $id);
 if($user !== null){
   $response["user"]=$user->toArray();
  }else{
    $response["message"]="No user found";
    }

  echo json_encode($response);
  return;
}

