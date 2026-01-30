<?php

class Request
{
    public static function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
    
    public static function post(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }
    
    public static function query(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }
    
    public static function all(): array
    {
        return array_merge($_GET, $_POST);
    }
    
    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    public static function isPost(): bool
    {
        return self::method() === 'POST';
    }
    
    public static function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
    
    public static function file(string $key)
    {
        return $_FILES[$key] ?? null;
    }
    
    public static function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}