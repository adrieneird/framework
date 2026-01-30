<?php

abstract class Form
{
	public $class;
	public $dto;
    public $fields = [];
	
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
	
	public function render(): void
    {
		echo '<form method="POST" action="">';
        foreach ($this->fields as $name => $type) {
            $value = $this->dto->$name ?? null;

            $file = BASE_PATH . '/input/' . $type . '.php';

            if (!file_exists($file)) {
                throw new Exception("Input type '$type' - '$file' not found");
            }

            include $file;
        }
		echo '<input type="hidden" name="_csrf" value="' . htmlspecialchars(self::getCsrf()) . '">';
		echo '</form>';
    }
	
	public function validate() {
		if (!$this->validateCsrf()) {
			throw new RuntimeException('CSRF invalide');
		}
		$this->consumeCsrf();
		
		if (isset($this->class)) {
			$this->dto = new $this->class;
			
			foreach ($this->fields as $name => $type) {
				if (property_exists($this->dto, $name)) {
					$this->dto->$name = Request::post($name);
				}
			}
		}		
	}
	
	public function load() {
		if (isset($this->class)) {
			$this->dto = new $this->class;
		}
	}
	
	abstract public function process();
}