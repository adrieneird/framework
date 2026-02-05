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
                        throw new RuntimeException('Wrong login or password.');
                    }
                }
            } catch (RuntimeException $e) {
                $form->addFormError($e->getMessage());
                Response::render("userlogin", [
                    "form" => $form
                ]);
                return;
            }
		}
		
		Response::render("userlogin", ["form" => $form]);
    }
}