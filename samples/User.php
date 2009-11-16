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
			'age' => 'UserAge'
		)
	);
	
	public function __construct(array $init = array()) {
		parent::__construct($init);
	}
}