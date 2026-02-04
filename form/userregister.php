<?php

class UserRegister extends Form
{
	public function __construct() {
		$this->setClass(User::class);
		$this->fields = [
			'email' => (new Input('email', 'email'))->required()->email()->max(255),
			'password' =>  (new Input('password', 'password'))->required()->min(8)->max(255),
			'submit' => (new Input('submit', 'submit')),
		];
	}
	
	public function process(): ?User {
		return $this->dto->register();
	}
}