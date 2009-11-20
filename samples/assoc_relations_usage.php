<?php

/**
 * Assuming that sphorm is installed and configured.
 * 
 * These examples are Based on definitions from:
 *  - mapping_one_to_one.php
 *  - mapping_one_to_many.php
 */

/**
 * One to One
 */

/*
 * gets user from db. 
 * homeProfile and workProfile are empty!!!
 */
$user = User()->get(1);

/**
 * This will trigger lazy loading and now homeProfile is loaded from DB.
 * workProfile is still 
 */
doSomeStuff( $user->homeProfile );
// or just
$user->homeProfile; 


/**
 * This will trigger lazy loading and load workProfile from DB.
 */
$user->workProfile;


/**
 * One to Many
 */


/*
 * gets user from db. 
 * addresses is empty!!!
 */
$user = User()->get(1);

/**
 * This will trigger lazy loading and load _all_ addresses for user 1
 * It will contain an array of Address instances.
 */
$user->addresses;
// or
foreach ($user->addresses as $addr) {
	// do some stuff with address $addr
} 

?>
