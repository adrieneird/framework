<?php

class LoginController extends Controller
{
    public static function formAction()
    {
        self::requireGuest();

		$form = new UserLogin();
		
		if (Request::isPost()) {
            try {
                if ($form->validate()) {
                    $user = $form->process();
                    if ($user) {
                        $form->clearRateLimit();
                        Response::redirect('profile', 'form');
                    } else {
                        $form->hitRateLimit();
                    }
                }
            } catch (RuntimeException $e) {
                Response::render("userlogin", [
                    "form" => $form,
                    "error" => $e->getMessage()
                ]);
                return;
            }
		}
		
		Response::render("userlogin", ["form" => $form]);
    }
}