<?php

class LoginController
{
    public static function logoutAction()
    {
		User::logout();
		
		Response::redirectUrl("index.php");
    }
}