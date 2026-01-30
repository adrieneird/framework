<?php

class LoginController
{
    public static function formAction()
    {
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