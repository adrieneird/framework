<?php

class ProfileController extends Controller
{
    public static function formAction()
    {
        self::requireAuth();

		$form = new UserProfile();
        $user = User::current();
		
		if (Request::isPost()) {
			if ($form->validate()) {
                $user = $form->process();
                if ($user) {
                    Response::redirect('profile', 'form');
                }
			}
		} else {
			$form->loadDto($user);
		}
		
		Response::render("userprofile", ["form" => $form, "username" => $user->email]);
    }
}