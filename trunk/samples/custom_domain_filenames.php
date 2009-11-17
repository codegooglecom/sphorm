<?php
/**
 * sphorm will look for your domain classes in this folder.
 * Can be relative or absolute path.
 */
define('SPHORM_DOMAIN_DIR', 'classes');

/**
 * Custom filenaming.
 * {clazz} - is your _exact_ class name. It's case sensitive.
 * e.g. class.User.php, class.Book.php
 */
define('SPHORM_DOMAIN_PATTERN', 'class.{clazz}.php');

// or

/**
 * e.g. User.inc.php, Book.inc.php
 */
define('SPHORM_DOMAIN_PATTERN', '{clazz}.inc.php');


// this one is _after_ you've defined previous consts
require "[sphorm-install-dir]/inc.php";

?>
