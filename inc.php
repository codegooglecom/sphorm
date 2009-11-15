<?php
if (!defined('SPHORM_HOME')) {
	define('SPHORM_HOME', $_SERVER['DOCUMENT_ROOT'] . '/sphorm');
}

require SPHORM_HOME . '/conf/DataSource.php';
require SPHORM_HOME . '/conf/BootStrap.php';
require SPHORM_HOME . '/conf/Beans.php';
require SPHORM_HOME . '/lib/Db.php';
require SPHORM_HOME . '/lib/Sphorm.php';
require SPHORM_HOME . '/lib/Debug.php';

Sphorm::init();
?>
