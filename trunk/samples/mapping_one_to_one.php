<?php
/**
 * Part 2: one to one mapping
 * 
 *  - One to One mapping is determined by '$hasOne' static var.
 *  - Currently only lazy init is supported.
 *  - Can have multiple One to One relations (brothers).
 *  
 *  Let's assume we have defined Profile domain. Our user can have home and work profiles.
 */
class User extends Sphorm {
	
	static $mapping = array(
		// ...
	);
	
	// ... other code
	
	/**
	 * Has 2 brothers: homeProfile, workProfile.
	 * Usage: 
	 * $obj->homeProfile and $obj->workProfile
	 * Both will return instances of Profile 
	 * 
	 * 'type' is the type of brother, in this case is Profile. It's domain name, not table name.
	 * 'join' is the common/join column name (currently supported only 1 column, multiple columns will come soon)
	 * 
	 */
	static $hasOne = array(
		'homeProfile' => array(
			'type' => 'Profile',
			'join' => 'ProfileUserID'
		),
		'workProfile' => array(
			'type' => 'Profile',
			'join' => 'ProfileUserID'
		),
	);
	
	// ... other code
	
	/**
	 * This should be included in _all_ your domain classes. Do NOT change this.
	 */
	public function __construct(array $init = array()) {
		// ... other code
	}
}
