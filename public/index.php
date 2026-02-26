<?php
require_once __DIR__ . '/../vendor/autoload.php';

$routes = require_once __DIR__ . '/../routes/web.php';
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (isset($routes[$path])) {
    $controllerClass = "App\\Controllers\\" . $routes[$path]['controller'];
    $method = $routes[$path]['method'];

    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();
        $controller->$method();
        exit;
    } else {
        http_response_code(500);
        echo "Controller $controllerClass not found";
        exit;
    }
}

http_response_code(404);
echo "404 Not Found";
