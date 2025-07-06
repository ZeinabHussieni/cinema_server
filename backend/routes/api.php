<?php 

// This block is used to extract the route name from the URL
//----------------------------------------------------------
// Define your base directory 
$base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove the base directory from the request if present
if (strpos($request, $base_dir) === 0) {
    $request = substr($request, strlen($base_dir));
}

// Ensure the request is at least '/'
if ($request == '') {
    $request = '/';
}




//Routing starts here (Mapping between the request and the controller & method names)
//It's an key-value array where the value is an key-value array
//----------------------------------------------------------
$apis = [
    '/user'         => ['controller' => 'UserController', 'method' => 'getUser'],
    '/delete_userbyId'         => ['controller' => 'UserController', 'method' => 'DeleteUser'],
    '/update_user'         => ['controller' => 'UserController', 'method' => 'updateUser'],
    '/create_user'         => ['controller' => 'UserController', 'method' => 'createUser'],
    '/get_userbyemail'    => ['controller' => 'UserController', 'method' => 'getUserByEmail'],


];

//----------------------------------------------------------


//Routing Logic here 
//This is a dynamic logic, that works on any array... 
//----------------------------------------------------------
// === Route Matching and Execution === //
if (isset($apis[$request])) {
    $controller_name = $apis[$request]['controller'];
    $method_name     = $apis[$request]['method'];

    // Load the controller file
    $controller_path = __DIR__ . "/../controllers/{$controller_name}.php";


    if (file_exists($controller_path)) {
        require_once $controller_path;

        if (class_exists($controller_name)) {
            $controller = new $controller_name();

            if (method_exists($controller, $method_name)) {
                $controller->$method_name();
            } else {
                echo json_encode(["error" => "Method '{$method_name}' not found in {$controller_name}"]);
            }

        } else {
            echo json_encode(["error" => "Class '{$controller_name}' does not exist"]);
        }

    } else {
        echo json_encode(["error" => "Controller file '{$controller_name}.php' not found"]);
    }

} else {
    http_response_code(404);
    echo json_encode(["error" => "Route '{$request}' not found"]);
}