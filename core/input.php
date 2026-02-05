<?php

class Input
{
    public string $name;
    public string $type = 'text';
    public mixed $value = null;
    public array $rules = [];
    public array $errors = [];

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function validate(): bool
    {
        $this->errors = [];
        foreach ($this->rules as $rule) {
            $result = $rule($this->value);
            if ($result !== true) {
                $this->errors[] = $result;
            }
        }
        return empty($this->errors);
    }

    public function render(): string
    {
        $name = $this->name ?? '';
        $value = $this->value ?? null;

        $file = BASE_PATH . '/input/' . $this->type . '.php';

        if (!file_exists($file)) {
            throw new RuntimeException("Input type '$this->type' - '$file' not found");
        }

        $html = !$this->errors ? '<div class="form-field">' : '<div class="form-field has-error">';

        ob_start();
        include $file;
        $html .= ob_get_clean();

        if (!empty($this->errors)) {
            $html .= '<ul class="input-errors">';
            foreach ($this->errors as $error) {
                $html .= '<li>'.htmlspecialchars($error).'</li>';
            }
            $html .= '</ul>';
        }

        $html .= '</div>';

        return $html;
    }

    // RULES

    public function required(): self
    {
        $this->rules['required'] = fn($v) => ($v !== null && $v !== '') ? true : 'This field is required';
        return $this;
    }

    public function email(): self
    {
        $this->rules['email'] = fn($v) => filter_var($v, FILTER_VALIDATE_EMAIL) ? true : 'Invalid email format';
        return $this;
    }

    public function min(int $length): self
    {
        $this->rules['min'] = fn($v) =>
        ($v === null || $v === '') // truthy if empty
            ? true
            : (strlen($v) >= $length ? true : "Minimum $length characters required");

        return $this;
    }

    public function max(int $length): self
    {
        $this->rules['max'] = fn($v) => strlen($v) <= $length ? true : "Maximum $length characters allowed";
        return $this;
    }
}