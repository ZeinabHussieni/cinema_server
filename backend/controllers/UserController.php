<?php 

require_once __DIR__ . '/../models/User.php';       
require_once __DIR__ . '/../services/UserService.php'; 
require_once __DIR__ . '/BaseController.php';         
 


class UserController extends BaseController {

    public function getUser() {
       try {
           if(isset($_GET["id"])){
           $id = $_GET["id"];
           $user = User::find($this->mysqli, $id);

            if (!$user) {
                return $this->error("User not found", 404);
            }

           return $this->respondSuccess(['user' => $user->toArray()]);
        }} catch (Exception $e) {
        $this->error($e->getMessage(), 500);
        }
        
    }
    

    public function getUserByEmail() {
         try {
           $email = $_GET["email"] ?? null;
           $password = $_GET["password"] ?? null;

           if (!$email || !$password) {
             return $this->error("Email and password are required", 400);
           }

           $user = User::findByEmail($this->mysqli, $email);

           if (!$user) {
             return $this->error("No user found with this email", 404);
            }

            if (!password_verify($password, $user->getPassword())) {
             return $this->error("Incorrect password", 401);
            }

           return $this->respondSuccess($user->toArray());

        } catch (Exception $e) {
         return $this->error($e->getMessage(), 500);
        }
    }
    

    public function updateUser() {
      try {
         $required = ["id", "email", "phoneNumber", "password", "favoriteGenres", "paymentMethod", "communicationPrefs"];

         foreach ($required as $field) {
             if (empty($_POST[$field])) {
                 return $this->error("Missing field: $field", 400);
                }
            }

         $updated = User::updateUser(
             $this->mysqli,
             (int) $_POST['id'],
             $_POST['email'],
             $_POST['phoneNumber'],
             $_POST['password'],
             $_POST['favoriteGenres'],
             $_POST['paymentMethod'],
             $_POST['communicationPrefs']
            );

         if ($updated) {
             return $this->respondSuccess(["message" => "User updated successfully"]);
            } else {
             return $this->error("Failed to update user", 500);
            }

        } catch (Exception $e) {
         return $this->error("Server error: " . $e->getMessage(), 500);
        }
    }


    public function createUser() {
     try {
         $required = ["email", "phoneNumber", "password", "favoriteGenres", "paymentMethod", "communicationPrefs"];
 
         foreach ($required as $field) {
             if (empty($_POST[$field])) {
                return $this->error("Missing field: $field", 400);
             }
            }

          $inserted = User::insertUser(
             $this->mysqli,
             $_POST['email'],
             $_POST['phoneNumber'],
             $_POST['password'],
             $_POST['favoriteGenres'],
             $_POST['paymentMethod'],
             $_POST['communicationPrefs']
            );

          if ($inserted) {
             return $this->respondSuccess(["message" => "User created successfully"]);
            } else {
             return $this->error("User creation failed", 500);
            }

        } catch (Exception $e) {
          return $this->error($e->getMessage(), 500);
        }
    }


   
    public function DeleteUser(){
 
        if (isset($_GET["id"])) {
          $id = (int)$_GET["id"];

        try{
        $success = User::delete($this->mysqli, $id);
        $this->respondSuccess($success);
        }catch(Exception $e){
            $this->error($e->getMessage(), 500);
        }
    }}



}
