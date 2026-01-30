<?php

class DbInstall
{

    public static function install(): void
    {
        $models = [];

        foreach (glob(BASE_PATH . '/model/' . '*.php') as $file) {
            $class = ucfirst(basename($file, '.php'));

            if (class_exists($class) && is_subclass_of($class, Model::class)) {
                $models[] = $class;
            }
        }

        usort($models, fn($a, $b) =>
            $a::install_order() <=> $b::install_order()
        );

        $db = Database::getInstance();

        foreach ($models as $model) {
            $db->exec($model::create_table_sql());
        }

        foreach ($models as $model) {
            foreach ($model::fk_sql() as $sql) {
                $db->exec($sql);
            }
        }
    }
	
	public static function uninstall(): void
	{
		$models = [];

        foreach (glob(BASE_PATH . '/model/' . '*.php') as $file) {
            $class = ucfirst(basename($file, '.php'));

            if (class_exists($class) && is_subclass_of($class, Model::class)) {
                $models[] = $class;
            }
        }

        usort($models, fn($a, $b) =>
            $b::install_order() <=> $a::install_order()
        );

        $db = Database::getInstance();

        foreach ($models as $model) {
            $db->exec("DROP TABLE IF EXISTS ".$model);
        }
	}
}
