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

if (isset($_POST["email"], $_POST["phoneNumber"], $_POST["password"])) {
    $email = $_POST["email"];
    $phoneNumber = $_POST["phoneNumber"];
    $password = $_POST["password"];
    $success = User::insertUser($mysqli,$email,$phoneNumber,$password); 

    if ($success) {
        $response["status"] = 200;
        $response["message"] = "User registered successfully";
    } else {
        $response["status"] = 500;
        $response["message"] = "Error inserting user";
    }
} else {
    $response["status"] = 400;
    $response["message"] = "Missing fields";
}
echo json_encode($response);