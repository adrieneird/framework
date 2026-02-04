<?php

class RegisterController extends Controller
{
    public static function formAction()
    {
        self::requireGuest();

		$form = new UserRegister();
		
		if (Request::isPost()) {
			if ($form->validate()) {
                $user = $form->process();
                if ($user) {
                    Response::redirect('login', 'form');
                }
			}
		}
		
		Response::render("userregister", ["form" => $form]);
    }
}