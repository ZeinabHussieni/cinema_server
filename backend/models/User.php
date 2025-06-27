<?php
require_once("Model.php");

class User extends Model{
    private $id;
    private $phoneNumber;
    private $email;
    private $password;
    private $favoriteGenres;
    private $paymentMethod;
    private $communicationPrefs;

    protected static string $table = "users";
    public function __construct(array $data){
        $this->id = $data["id"];
        $this->email = $data["email"];
        $this->phoneNumber = $data["phoneNumber"];
        $this->password = $data["password"];
        $this->favoriteGenres = $data["favoriteGenres"];
        $this->paymentMethod = $data["paymentMethod"];
        $this->communicationPrefs = $data["communicationPrefs"];     
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
    public function getFavoriteGenres(): string{
        return $this->favoriteGenres;
    }
    public function getPaymentMethod(): string{
        return $this->paymentMethod;
    }
    public function getCommunicationPrefs(): string{
        return $this->communicationPrefs;
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
    public function setFavoriteGenres(string $favoriteGenres){
        $this->favoriteGenres=$favoriteGenres;
    }
    public function setPaymentMethod(string $paymentMethod){
        $this->paymentMethod=$paymentMethod;
    }
    public function setCommunicationPrefs(string $communicationPrefs){
        $this->communicationPrefs=$communicationPrefs;
    }
    public function toArray(){
        return [$this->id, $this->email,$this->phoneNumber,$this->password,$this->favoriteGenres,$this->paymentMethod,
                $this->communicationPrefs];
    }

  public static function insertUser(mysqli $mysqli,string $email,string $phoneNumber,string $password,string $favoriteGenres,string $paymentMethod,string $communicationPrefs):bool {
    $sql = sprintf("Insert into %s (email,phoneNumber,password,favoriteGenres,paymentMethod,communicationPrefs) values(?,?,?,?,?,?)", 
                   static::$table);
                   
    $query=$mysqli->prepare($sql);

    if(!$query) {
    error_log("Prepare failed: " . $mysqli->error); 
    return false;
     }
    $hashed=password_hash($password,PASSWORD_DEFAULT);
    $query->bind_param("ssssss",$email,$phoneNumber,$hashed,$favoriteGenres,$paymentMethod,$communicationPrefs);
    $executed = $query->execute();
    if (!$executed) {
        error_log("Execute failed: " . $query->error);
        return false;
    }
    return true;  
 }
  public static function updateUser(mysqli $mysqli,int $id,string $email,string $phoneNumber,string $password,string $favoriteGenres,string $paymentMethod,string $communicationPrefs):bool {
    $sql = sprintf("update %s SET email = ? ,phoneNumber =?, password=?, favoriteGenres=?,paymentMethod=?,communicationPrefs=? where id=?", 
                   static::$table);
                   
    $query=$mysqli->prepare($sql);

    if(!$query) return false;

    $hashed=password_hash($password,PASSWORD_DEFAULT);

    $query->bind_param("ssssssi",$email,$phoneNumber,$hashed,$favoriteGenres,$paymentMethod,$communicationPrefs,$id);
   return $query->execute();
   
 }
}