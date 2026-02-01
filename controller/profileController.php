<?php

class ProfileController extends Controller
{
    public static function formAction()
    {
        self::requireAuth();

		$form = new UserProfile();
		
		if (Request::isPost()) {
			$form->validate();
			$user = $form->process();
			if ($user) {
				Response::redirect('profile', 'form');
			}
		} else {
			$form->load();
		}
		
		Response::render("userprofile", ["form" => $form]);
    }
}