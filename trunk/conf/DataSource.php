<?php
class DataSource {
	static $default = array(
		'engine' => 'mysql',
		'host' => 'localhost',
		'port' => 3306,
		'schema' => 'default-db',
		'username' => 'root',
		'password' => '',
		'dbCreate' => 'update' // one of 'create', 'create-drop','update'
	);

	static $development = array(
		'schema' => 'phorm-demo',
		'username' => 'root',
		'password' => '!123_x'
		);

	static $test = array(
		'schema' => 'test-db',
		'username' => 'root',
		'password' => ''
		);

	static $production = array(
		'schema' => 'prod-db',
		'username' => 'root',
		'password' => ''
		);

	public static function getSettings($env = 'development') {
		if ($env == 'production') {
			return array_merge(self::$default, self::$production);
		} else if ($env == 'test') {
			return array_merge(self::$default, self::$test);
		} else {
			return array_merge(self::$default, self::$development);
		}
	}
}

?>