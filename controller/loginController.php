<?php

class LoginController extends Controller
{
    public static function formAction()
    {
        self::requireGuest();

		$form = new UserLogin();
		
		if (Request::isPost()) {
			$form->validate();
			$user = $form->process();
			if ($user) {
				Response::redirect('profile', 'form');
			}
		}
		
		Response::render("userlogin", ["form" => $form]);
    }
}