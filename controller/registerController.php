<?php

class RegisterController
{
    public static function formAction()
    {
		$form = new UserRegister();
		
		if (Request::isPost()) {
			$form->validate();
			$user = $form->process();
			if ($user) {
				Response::redirect('login', 'form');
			}
		}
		
		Response::render("userregister", ["form" => $form]);
    }
}