<?php
/**
 * Has 3 environment regions: development, test, production
 */

class DataSource {
	
	/**
	 * Default settings, available to _all_ env.  
	 */
	static $default = array(
		'engine' => 'mysql',
		'host' => 'localhost',
		'port' => 3306,
		'schema' => 'default-db',
		'username' => 'root',
		'password' => '',
		'dbCreate' => 'update' // one of 'create', 'create-drop','update'
		);
		
		
		/**
		 * Each env can override one or more properties
		 */
		static $development = array(
		'schema' => 'dev-db',
		'username' => 'root',
		'password' => ''
		);

		/**
		 * This one overrides host property. Other settings are ingerited from $default. 
		 */
		static $test = array(
		'host' => '192.168.0.10',
		'schema' => 'test-db',
		'username' => 'root',
		'password' => ''
		);

		static $production = array(
		'schema' => 'prod-db',
		'username' => 'root',
		'password' => ''
		);
		
		// makes inheritance to work...
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