<?php
abstract class Model{
 protected static string $table;
 protected static $primary_key = "id";
 
 public static function find(mysqli $mysqli, int $id){
    $sql = sprintf("Select * from %s WHERE %s = ? ",
                    static::$table,
                    static::$primary_key);
                    
    $query = $mysqli->prepare($sql);
    $query -> bind_param("i",$id);
    $query -> execute();

    $data=$query->get_result()->fetch_assoc();
    return $data ? new static($data):null;

 }

}