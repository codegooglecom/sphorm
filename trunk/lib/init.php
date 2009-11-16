<?php
function __autoload($clazz) {
	require_once SPHORM_DOMAIN_DIR . '/' . str_replace('{clazz}', $clazz, SPHORM_DOMAIN_PATTERN);
}
?>