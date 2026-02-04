<?php

class ProfileController extends Controller
{
    public static function formAction()
    {
        self::requireAuth();

		$form = new UserProfile();
		
		if (Request::isPost()) {
			if ($form->validate()) {
                $user = $form->process();
                if ($user) {
                    Response::redirect('profile', 'form');
                }
			}
		} else {
			$form->loadDto(User::current());
		}
		
		Response::render("userprofile", ["form" => $form]);
    }
}