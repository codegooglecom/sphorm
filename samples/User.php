<?php
class User extends Sphorm {
	// mapping
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
	 * Do NOT change this.
	 */
	public function __construct(array $init = array()) {
		parent::__construct($init);
	}
}
