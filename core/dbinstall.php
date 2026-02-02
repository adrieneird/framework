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
            $a::installOrder() <=> $b::installOrder()
        );

        $db = Database::getInstance();

        foreach ($models as $model) {
            $db->exec($model::createTableSql());
        }

        foreach ($models as $model) {
            foreach ($model::fkSql() as $sql) {
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
            $b::installOrder() <=> $a::installOrder()
        );

        $db = Database::getInstance();

        foreach ($models as $model) {
            $db->exec("DROP TABLE IF EXISTS ".$model);
        }
	}
}
