<?php
if (!defined('SPHORM_HOME')) {
	define('SPHORM_HOME', $_SERVER['DOCUMENT_ROOT'] . '/sphorm');
}

if (!defined('SPHORM_DOMAIN_DIR')) {
	if (file_exists(SPHORM_HOME . '/domains')) {
		define('SPHORM_DOMAIN_DIR', SPHORM_HOME . '/domains');
	} else {
		define('SPHORM_DOMAIN_DIR', SPHORM_HOME);
	}
} else {
	if (!file_exists(SPHORM_DOMAIN_DIR)) {
		trigger_error('"SPHORM_DOMAIN_DIR" is not a valid directory. Please correct the path and try again.', E_USER_ERROR);
	}
}

if (!defined('SPHORM_DOMAIN_PATTERN')) {
	define('SPHORM_DOMAIN_PATTERN', '{clazz}.php');
}

require SPHORM_HOME . '/lib/init.php';
require SPHORM_HOME . '/conf/Config.php';
require SPHORM_HOME . '/conf/DataSource.php';
require SPHORM_HOME . '/conf/Beans.php';
require SPHORM_HOME . '/lib/Db.php';
require SPHORM_HOME . '/lib/extra/AbstractCall.php';
require SPHORM_HOME . '/lib/Sphorm.php';
require SPHORM_HOME . '/lib/Debug.php';

if (!defined('SPHORM_ENV')) {
	define('SPHORM_ENV', 'development');
}

Sphorm::init();
?>
