<?php
class Debug {
	public static function dump($obj, $exit = true) {
		echo "<pre>";
		var_dump($obj);
		echo "</pre>";

		if ($exit) {
			exit;
		}
	}
}
