<?php
abstract class AbstractCall {
	protected $model;
	protected $callName;
	protected $callArguments;
	
	// not used yet
	static $operators = array(
		'LessThan',
		'LessThanEquals',
		'GreaterThan',
		'GreaterThanEquals',
		'Between',
		'Like',
		'IsNotNull',
		'IsNull',
		'Not',
		'Equal',
		'NotEqual',
		'And',
		'Or'
	);

	public abstract function call();

	public function __construct(Sphorm $model, $callName, $callArguments) {
		$this->model = $model;
		$this->callName = $callName;
		$this->callArguments = $callArguments;
	}
}