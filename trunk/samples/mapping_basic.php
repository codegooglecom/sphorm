<?php
/**
 * Part 1: basic mapping
 * 
 * This file should be named: User.php
 */
class User extends Sphorm {
	/**
	 * Column mapping. 
	 * 
	 * 'id' is required.
	 * 'column' is the column name for the PK (currently only PK with 1 column are supported).
	 * 'generator' defines how id is generated(currently only auto aka auto_increment is supported). 
	 * 
	 * If 'table' is not specified, class name is used as table name.
	 * 
	 * 'columns' is required if you need database generation feature.
	 * Columns that are not listed here will be mapped automatically with real column names.
	 * Note: db generation will not work though... only retreival and their
	 * 
	 * To map a property to a column, just specify 'name' => 'RealColumnName'
	 * For more complex definition, use assoc array. See example below.
	 * 
	 * Example description:
	 * Domain class User is mapped to 'users' table.
	 * PK is UserID column. Autoincremented.
	 * 
	 * Has 2 properties defined: name, age
	 * 'name' is mapped to 'UserName' column.
	 * 'age' is mapped to 'UserAge' column, is of int(11) type, has default value of 18 and is not nullable 
	 */
	static $mapping = array(
		'table' => 'users',
		'id' => array(
			'column'=> 'UserID',
			'generator' => 'auto'
		),
		'columns' => array(
			'name' => 'UserName',
			'age' => array(
						'name' => 'UserAge',
						'type' => 'int(11)',
						'default' => 18,
						'nullable' => false
					)
		)
	);
	
	/**
	 * This should be included in _all_ your domain classes. Do NOT change this.
	 */
	public function __construct(array $init = array()) {
		parent::__construct($init);
	}
}
