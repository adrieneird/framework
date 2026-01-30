<?php

class Router
{
    public static function route(): void
    {
        $controller = $_GET['page'] ?? 'index';
        $action = $_GET['action'] ?? 'index';

        $controllerClass = ucfirst($controller) . 'Controller';
        $actionFunction = ucfirst($action) . 'Action';

        if (!class_exists($controllerClass)) {
            Response::redirect("index", 404);
            //echo 'Controller not found';
            return;
        }

        if (!method_exists($controllerClass, $actionFunction)) {
            Response::redirect("index", 404);
            //echo 'Action '.$actionFunction.' not found';
            return;
        }

        $controllerClass::$actionFunction();
    }
}