<?php

class ResponseService {

    public static function success_response($payload, $status = 200){
        http_response_code($status); 
        return json_encode($payload);
    }

    public static function error_response($message, $status = 400){
        http_response_code($status);
        return json_encode([
            "status" => $status,
            "error" => $message
        ]);
    }

}
