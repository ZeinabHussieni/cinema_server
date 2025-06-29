<?php
require("../models/User.php");
require("../connection/connection.php");
$response = [];

if(isset($_GET["email"]) && isset($_GET["password"])){

 $email =$_GET["email"];
 $password =$_GET["password"];
 $user = User::findByEmail($mysqli, $email);

  if($user !== null && password_verify($password, $user->getPassword())){
   $response["user"]=$user->toArray();
   }else{
    $response["message"]="No user found with this email or password";
    }

   echo json_encode($response);
   return;
}
