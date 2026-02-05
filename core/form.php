<?php

abstract class Form
{
	private string $class;
	protected ?Model $dto =  null;
    protected array $fields = [];
    protected ?string $rateLimitKey = null;
    protected int $rateLimitAttempts = 3;
    protected int $rateLimitMinutes = 15;
	
    protected function setClass(string $class): void
    {
        if (!is_subclass_of($class, Model::class)) {
            throw new RuntimeException("Form class must be a Model subclass, got: $class");
        }
        $this->class = $class;
    }

    // Rate limit

    protected function checkRateLimit(): bool
    {
        if ($this->rateLimitKey === null) {
            return true; // No rate limiting for this form
        }
        
        $identifier = Request::post($this->rateLimitKey, '');
        $key = 'rate_limit_' . static::class . '_' . md5($identifier);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'attempts' => 0,
                'reset_at' => time() + ($this->rateLimitMinutes * 60)
            ];
        }
        
        $data = $_SESSION[$key];
        
        // Check if lockout expired
        if (time() >= $data['reset_at']) {
            $_SESSION[$key] = [
                'attempts' => 0,
                'reset_at' => time() + ($this->rateLimitMinutes * 60)
            ];
            return true;
        }
        
        // Check if too many attempts
        return $data['attempts'] < $this->rateLimitAttempts;
    }

    protected function hitRateLimit(): void
    {
        if ($this->rateLimitKey === null) {
            return;
        }
        
        $identifier = Request::post($this->rateLimitKey, '');
        $key = 'rate_limit_' . static::class . '_' . md5($identifier);
        
        if (isset($_SESSION[$key])) {
            $_SESSION[$key]['attempts']++;
        }
    }

    public function clearRateLimit(): void
    {
        if ($this->rateLimitKey === null) {
            return;
        }
        
        $identifier = Request::post($this->rateLimitKey, '');
        $key = 'rate_limit_' . static::class . '_' . md5($identifier);
        unset($_SESSION[$key]);
    }

    // CSRF
    
	protected static function getCsrf(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    protected function validateCsrf(): bool
    {
        return isset($_POST['_csrf'])
            && isset($_SESSION['_csrf'])
            && hash_equals($_SESSION['_csrf'], $_POST['_csrf']);
    }

    protected function consumeCsrf(): void
    {
        unset($_SESSION['_csrf']);
    }
	
    // Data: Dto, Input, Post
	
    public function createDto(): void {
        if (!isset($this->class)) {
            throw new RuntimeException("Form class not found");
		}
        if (!is_subclass_of($this->class, Model::class)) {
            throw new RuntimeException("Form class must be a Model subclass, got: " . $this->class);
        }
        $this->dto = new $this->class;
    }

	public function loadDto(Model $dto): void {
        if (isset($this->class) && !($dto instanceof $this->class)) {
            throw new RuntimeException("DTO must be instance of {$this->class}");
        }
        $this->dto = $dto;
        $this->dtoToInput();
	}

    protected function dtoToInput(): void {
        if (!$this->dto) {
            $this->createDto();
        }
        foreach ($this->fields as $name => $input) {
            if (property_exists($this->dto, $name)) {
                $input->value = $this->dto->$name;
            }
        }
    }

    protected function inputToDto(): void {
        if (!$this->dto) {
            $this->createDto();
        }
        foreach ($this->fields as $name => $input) {
            if (property_exists($this->dto, $name)) {
                $this->dto->$name = $input->value;
            }
        }
    }

    protected function postToInput(): void {
        foreach ($this->fields as $name => $input) {
            $input->value = Request::post($name);
        }
    }

    // Actions

	public function render(): string
    {
        $html = '';
		$html .= '<form method="POST" action="">';
        foreach ($this->fields as $name => $input) {
            $html .= $input->render();
        }
		$html .= '<input type="hidden" name="_csrf" value="' . htmlspecialchars(self::getCsrf()) . '">';
		$html .= '</form>';

        return $html;
    }
	
	public function validate(): bool {
        if (!$this->checkRateLimit()) {
            throw new RuntimeException('Too many attempts. Please try again in ' . $this->rateLimitMinutes . ' minutes.');
        }

		if (!$this->validateCsrf()) {
			throw new RuntimeException('invalid CSRF');
		}
		$this->consumeCsrf();
		
		$this->createDto();
        $this->postToInput();
			
        $valid = true;
        foreach ($this->fields as $name => $input) {
            if (!$input->validate()) {
                $valid = false;
            }
        }

        if (!$this->validateForm()) {
            $valid = false;
        }

        if ($valid) {
            $this->inputToDto();
        }
        return $valid;
	}

    protected function validateForm(): bool
    {
        return true;
    }

    abstract public function process();
}