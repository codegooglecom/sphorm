<?php
/**
 * Core config file.
 * Environment aware.
 */
class Config {
	static $default = array(
		'saveStrategy' => 'ignore-undefined' // 'halt-on-undefined', 'ignore-undefined', 'no-checking', 'alter-on-undefined'
	);

	static $development = array(
	);

	static $test = array(
	);

	static $production = array(
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