<?php 
require("../models/User.php");
require("../connection/connection.php");

$response = [];


if (isset($_POST["id"],$_POST["email"], $_POST["phoneNumber"], $_POST["password"],$_POST["favoriteGenres"],$_POST["paymentMethod"],$_POST["communicationPrefs"])) {
    $id=(int)$_POST["id"];
    $email = $_POST["email"];
    $phoneNumber = $_POST["phoneNumber"];
    $password = $_POST["password"];
    $favoriteGenres=$_POST["favoriteGenres"];
    $paymentMethod= $_POST["paymentMethod"];
    $communicationPrefs=$_POST["communicationPrefs"];
    $success = User::updateUser($mysqli,$id,$email,$phoneNumber,$password, $favoriteGenres, $paymentMethod, $communicationPrefs); 

    if ($success) {
        $response["status"] = 200;
        $response["message"] = "User updated successfully";
    } else {
        $response["status"] = 500;
        $response["message"] = "Error updating user";
    }
} else {
    $response["status"] = 400;
    $response["message"] = "Missing fields";
}
echo json_encode($response);