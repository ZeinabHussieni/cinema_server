<?php 

class RatingService {

    public static function ratingToArray($cinema_db){
        $results = [];

        foreach($cinema_db as $c){
             $results[] = $c->toArray(); //hence, we decided to iterate again on the cinema_db array and now to store the result of the toArray() which is an array. 
        } 

        return $results;
    }

}