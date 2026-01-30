<?php

class User extends Model
{
    public const INSTALL_ORDER = 1;

    public string $email = '';
    public string $password = '';
    public string $password_hash = '';
    public string $created_at;

    public static function create_table_sql(): string
    {
		$table = static::class;
        return "
        CREATE TABLE IF NOT EXISTS $table (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
        ";
    }

    public static function fk_sql(): array
    {
        return [];
    }
	
	public static function find_by_email(string $email): ?User
    {
        $db = Database::getInstance();
        $table = static::class;
		$stmt = $db->prepare("SELECT * FROM $table WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$data) return null;
        return static::from_array($data);
    }
	
	private function hashPassword(): void
	{
		$this->password_hash = password_hash($this->password, PASSWORD_DEFAULT);
		$this->password = '';
	}
	
	// ACTIONS
	
	public function register(): ?self
	{
		if (!empty($this->password)) {
			$this->hashPassword();
			
			$this->save(['email', 'password_hash']);
		}
		return null;
	}
	
	public function login(): ?self
    {
        $user = self::find_by_email($this->email);
        if (!$user) return null;
        if (!password_verify($this->password, $user->password_hash)) return null;

        $_SESSION['user_id'] = $user->id;
        return $user;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }
	
	public static function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function current(): ?self
    {
        return self::check() ? self::find_by_id($_SESSION['user_id']) : null;
    }
	
	public function update(): ?self
	{
		$save = [];
		if (!empty($this->email)) {
			$save[] = 'email';
		}
		if (!empty($this->password)) {
			$this->hashPassword();
			$save[] = 'password_hash';
		}
		
		if (!empty($save)) {
			if ($this->save($save)) {
				return $this;
			}
		}
		return null;
	}
}