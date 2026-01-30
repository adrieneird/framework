<?php

class UserRegister extends Form
{
	public function __construct() {
		$this->class = User::class;
		$this->fields = [
			'email' => 'email',
			'password' => 'password',
			'submit' => 'submit',
		];
	}
	
	public function process(): ?User {
		return $this->dto->register();
	}
}