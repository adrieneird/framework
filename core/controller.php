<?php

abstract class Controller
{
    protected static function requireGuest(string $controller ='index', string $action ='index'): void
    {
        if (User::check()) {
            Response::redirect($controller, $action);
            exit;
        }
    }

    protected static function requireAuth(string $controller = 'login', string $action = 'form'): void
    {
        if (!User::check()) {
            Response::redirect($controller, $action);
            exit;
        }
    }
}