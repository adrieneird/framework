<?php

class UserLogin extends Form
{
	public function __construct() {
		$this->setClass(User::class);
        $this->rateLimitKey = 'email';
        $this->fields = [
			'email' => (new Input('email', 'email'))->required()->email()->max(255),
			'password' =>  (new Input('password', 'password'))->required()->min(1)->max(255), // Do not give away minimum password length
			'submit' => (new Input('submit', 'submit')),
		];
	}
	
	public function process(): ?User {
		return $this->dto->login();
	}
}