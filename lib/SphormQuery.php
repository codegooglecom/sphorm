<?php
class SphormQuery extends Sphorm {
	public function execute($sql, $params = array()) {
		return parent::executeQuery($sql, $params);
	}

	public function __construct(array $init = array()) {
		parent::__construct($init);
	}

	public function save() {
		return false;
	}

	public function delete() {
		return false;
	}

	// more to come later...
}
?>