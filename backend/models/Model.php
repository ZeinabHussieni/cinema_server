<?php
abstract class Model{

 protected static string $table;
 protected static $primary_key = "id";
 
   public static function find(mysqli $mysqli, int $id){
          $sql = sprintf("Select * from %s WHERE %s = ? ",
                    static::$table,
                    static::$primary_key);
                    
      $query = $mysqli->prepare($sql);

      if(!$query){
            throw new Exception("Prepare Failed: ".$mysqli->error);
      }

      $query -> bind_param("i",$id);
      $query -> execute();

      if(!$query){
            throw new Exception("Execute Failed: ".$mysqli->error);
      }

      $data=$query->get_result()->fetch_assoc();
      return $data ? new static($data):null;

   }

   //create
   public static function create(mysqli $mysqli, array $data): bool {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
     
        $sql = "Insert INTO " . static::$table . " ($columns) VALUES ($placeholders)";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
          throw new Exception("Prepare Failed: " . $mysqli->error);
        }

        $types = str_repeat("s", count($data)); 
        $stmt->bind_param($types, ...array_values($data));

        if (!$stmt->execute()) {
          throw new Exception("Execute Failed: " . $stmt->error);
        }

        return true;
   }


   // delete by id
   public static function Delete(mysqli $mysqli, $id):bool{
       $sql = sprintf("Delete FROM %s WHERE %s = ?", static::$table, static::$primary_key);

        $query= $mysqli->prepare($sql);
        if (!$query) {
            throw new Exception("Prepare Failed: " . $mysqli->error);
        }

        $query->bind_param("i", $id);

        $res = $query->execute();

        if ($res) {
            throw new Exception("Execute Failed: " . $query->error);
        }

        return true;
    
   }

   //delete all function
   public static function DeleteAll(mysqli $mysqli):bool{
        $mysql= sprintf("Delete from %s",static::$table);

        $query=$mysqli->prepare($mysql);

        if (!$query) {
            throw new Exception("Prepare Failed: " . $mysqli->error);
        }

        if (!$query->execute()) {
            throw new Exception("Execute Failed: " . $query->error);
        }
        return true;
   }

}