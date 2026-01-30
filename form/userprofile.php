<?php

class UserProfile extends Form
{
	public function __construct() {
		$this->class = User::class;
		$this->fields = [
			'email' => 'email',
			'password' => 'password',
			'submit' => 'submit',
		];
	}
	
	public function load(): void {
		if (isset($this->class)) {
			$this->dto = User::current();
		}
	}
	
	public function process(): ?User {
		$this->dto->id = User::id();
		return $this->dto->update();
	}
}