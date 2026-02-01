<?php

spl_autoload_register(function ($class) {
	$paths = [
        BASE_PATH . '/core/',
		BASE_PATH . '/model/',
        BASE_PATH . '/controller/',
        BASE_PATH . '/form/',
    ];

    foreach ($paths as $path) {
        $file = $path . strtolower($class) . '.php';
        if (file_exists($file)) {
            require_once($file);
            return;
        }
    }

    //die("Class not found : $class");
});