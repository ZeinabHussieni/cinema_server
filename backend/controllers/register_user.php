<?php
require("../models/User.php");
require("../connection/connection.php");

$response = [];
if (isset($_POST["email"], $_POST["phoneNumber"], $_POST["password"],$_POST["favoriteGenres"],$_POST["paymentMethod"],$_POST["communicationPrefs"])) {
    $email = $_POST["email"];
    $phoneNumber = $_POST["phoneNumber"];
    $password = $_POST["password"];
    $favoriteGenres=$_POST["favoriteGenres"];
    $paymentMethod= $_POST["paymentMethod"];
    $communicationPrefs=$_POST["communicationPrefs"];
    $success = User::insertUser($mysqli,$email,$phoneNumber,$password, $favoriteGenres, $paymentMethod, $communicationPrefs); 


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
