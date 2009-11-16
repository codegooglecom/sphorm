<?php
if (!defined('SPHORM_HOME')) {
	define('SPHORM_HOME', $_SERVER['DOCUMENT_ROOT'] . '/sphorm');
}

require SPHORM_HOME . '/conf/DataSource.php';
require SPHORM_HOME . '/conf/Beans.php';
require SPHORM_HOME . '/lib/Db.php';
require SPHORM_HOME . '/lib/Core.php';
require SPHORM_HOME . '/lib/Sphorm.php';
require SPHORM_HOME . '/lib/Debug.php';

if (!defined('SPHORM_ENV')) {
	define('SPHORM_ENV', 'development');
}

Sphorm::init();
?>
