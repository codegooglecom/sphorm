<?php
abstract class AbstractCall {
	protected $model;
	protected $callName;
	protected $callArguments;
	
	public abstract function call();
	
	public function __construct(Sphorm $model, $callName, $callArguments) {
		$this->model = $model;
		$this->callName = $callName;
		$this->callArguments = $callArguments;
	}
}