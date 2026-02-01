<?php

class LoginController extends Controller
{
    public static function logoutAction()
    {
        self::requireAuth();

		User::logout();
		
		Response::redirectUrl("index.php");
    }
}