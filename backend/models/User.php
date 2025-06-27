<?php
require_once("Model.php");

class User extends Model{
    private $id;
    private $phoneNumber;
    private $email;
    private $password;

    protected static string $table = "users";
    public function __construct(array $data){
        $this->id = $data["id"];
        $this->email = $data["email"];
        $this->phoneNumber = $data["phoneNumber"];
        $this->password = $data["password"];
    }
    
    public function getId():int{
        return $this->id;
    }
    public function getPhoneNumber(): string{
        return $this->phoneNumber;
    }
    public function getEmail(): string{
        return $this->email;
    }
    public function getPassword(): string{
        return $this->password;
    }
    public function setPhoneNumber(string $phoneNumber){
        $this->phoneNumber=$phoneNumber;
    }
    public function setEmail(string $email){
        $this->email=$email;
    }
        public function setPassword(string $password){
        $this->password=$password;
    }
    public function toArray(){
        return [$this->id, $this->email,$this->phoneNumber];
    }

  public static function insertUser(mysqli $mysqli,string $email,string $phoneNumber,string $password):bool {
    $sql = sprintf("Insert into %s (email,phoneNumber,password) values(?,?,?)", 
                   static::$table);
                   
    $query=$mysqli->prepare($sql);

    if(!$query) return false;

    $hashed=password_hash($password,PASSWORD_DEFAULT);

    $query->bind_param("sss",$email,$phoneNumber,$hashed);
   return $query->execute();
   
 }

}