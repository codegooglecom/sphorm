<?php
require "[path-to-sphorm-dir]/inc.php";

require "[path-to-your-class]/User.php";

/**
 * User() is a PHP function!
 */


/**
 * Reading DB
 */

/**
 * Counts the number of instances in the database and returns the result
 */
$total = User()->count();


/**
 * Retrieves all of the domain class instances
 */
$allUsers = User()->all();


/**
 * Retrieves an instance of the domain class for the specified id, otherwise returns null
 */ 
$oneUser = User()->get(1024); 


/**
 * Retrieves an array of instances of the domain class for the specified ids, otherwise returns empty array
 */
$multipleUsers = User()->get(1024, 45, 56);
$multipleUsers = User()->get(array(1024, 45, 56));


/**
 * Finds and returns the first result for the given params (assoc array) or null if no instance was found
 * Keys are instance properties, or columns if they don't later don't have properties mapped with
 */
$foundOneUser = User()->find(array('name'=>'John'));


/**
 * Finds all of the domain class instances for the specified params
 */
$foundUsers = User()->findAll(array('name'=>'John'));



/**
 * Writing to BD
 */

/**
 * Create new instance
 */
$newUser = new User();
$newUser->name = 'Maria';
$newUser->age = 24;
//will not be persisted
$newUser->someOtherField = 'Some other value';


/**
 * Save it
 * If it exists, will be updated
 * Returns true or false
 */
$success = $newUser->save();


/**
 * Delete
 */
$badUser = User()->get(666);
if ($badUser != null) {
	$badUser->delete();
}


/**
 * To be continued...
 */

?>
