<?php

abstract class Model
{
    public const INSTALL_ORDER = 0;

    abstract public static function create_table_sql(): string;
    abstract public static function fk_sql(): array;

	public ?int $id = null;

    // Install order
    public static function install_order(): int
    {
        return static::INSTALL_ORDER;
    }
    
    // Hydrate
    public static function from_array(array $data)
    {
        $obj = new static();
        foreach ($data as $key => $value) {
            if (property_exists($obj, $key)) {
                $obj->$key = $value;
            }
        }
        return $obj;
    }
	
	protected function save(?array $fields = null): bool
	{
		$db = Database::getInstance();
		$table = static::class;

		$props = get_object_vars($this);
		unset($props['id']);

		if ($fields !== null) {
			$props = array_intersect_key($props, array_flip($fields));
		}

		if (empty($props)) {
			return false;
		}

		try {
			if (isset($this->id)) {
				// Update
				$sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($props)));
				$stmt = $db->prepare("UPDATE $table SET $sets WHERE id = :id");

				return $stmt->execute(array_merge($props, ['id' => $this->id]));
			} else {
				// Create
				$columns = implode(', ', array_keys($props));
				$placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($props)));
				$stmt = $db->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");

				if ($stmt->execute($props)) {
					$this->id = (int) $db->lastInsertId();
					return true;
				}
				return false;
			}
		} catch (\PDOException $e) {
			return false;
		}
	}

    public static function find_by_id(int $id): ?self
    {
        $db = Database::getInstance();
        $table = static::class;
        $stmt = $db->prepare("SELECT * FROM $table WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;
        return static::from_array($data);
    }
}