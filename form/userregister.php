<?php

class UserRegister extends Form
{
	public function __construct() {
		$this->setClass(User::class);
		$this->fields = [
			'email' => (new Input('email', 'email'))->required()->email()->max(255),
			'password' =>  (new Input('password', 'password'))->required()->min(8)->max(255),
			'password_confirm' =>  (new Input('password_confirm', 'password'))->required()->min(8)->max(255),
			'submit' => (new Input('submit', 'submit')),
		];
	}
	
    protected function validateForm(): bool
    {
        if ($this->fields['password']->value !== $this->fields['password_confirm']->value) {
            $this->fields['password_confirm']->errors[] = 'Passwords do not match';
            return false;
        }
        return true;
    }

	public function process(): ?User {
		return $this->dto->register();
	}
}