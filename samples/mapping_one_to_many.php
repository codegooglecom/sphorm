<?php
/**
 * Part 3: one to many mapping
 * 
 *  - One to Many mapping is determined by '$hasMany' static var.
 *  - Currently only lazy init is supported.
 *  - Can have multiple One to Many relations.
 *  
 *  Let's assume we have defined Address domain. Our user can have multiple addresses.
 */
class User extends Sphorm {
	
	static $mapping = array(
		// ...
	);
	
	// ... other codes
	
	/**
	 * Has 1 property: addresses.
	 * Usage: 
	 * $obj->addresses - will return an array of addresses 
	 * $obj->addToAddresses(new Address(...)) - will add one address to array of addresses.
	 * addTo* methods are determined on the fly, sphorm knows what to do...
	 * Note: $obj->address[] will _not_ work, i've tried ;)
	 *  
	 * 'type' and 'join' is the same as for One to One relation.
	 * 
	 */
	static $hasMany = array(
		'addresses' => array(
			'type' => 'Address',
			'join' => 'AddressUserID'
		),
	);
	
	// ... other codes
	
	/**
	 * This should be included in _all_ your domain classes. Do NOT change this.
	 */
	public function __construct(array $init = array()) {
		// ...
	}
}
