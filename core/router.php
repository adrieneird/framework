<?php

class Router
{
    public static function route(): void
    {
        $controller = Request::query('page', 'index');
        $action = Request::query('action', 'index');

        $controllerClass = ucfirst($controller) . 'Controller';
        $actionFunction = ucfirst($action) . 'Action';

        if (!class_exists($controllerClass)) {
            Response::redirectUrl("index.php", 404);
            return;
        }

        if (!is_subclass_of($controllerClass, Controller::class)) {
            Response::redirectUrl("index.php", 403);
            return;
        }

        if (!method_exists($controllerClass, $actionFunction)) {
            Response::redirectUrl("index.php", 404);
            return;
        }

        $controllerClass::$actionFunction();
    }
}