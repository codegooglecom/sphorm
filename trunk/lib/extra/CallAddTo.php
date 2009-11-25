<?php
class CallAddTo extends AbstractCall {
	public function call() {
		$propName = $this->callName;
		$arguments = $this->callArguments;
		
		if (empty($arguments)) {
			return false;
		}
		
		if (!isset($this->model->$propName)) {
			$propName{0} = strtolower($propName{0});
		}
		
		
		//should load from db if needed...
		$arr = $this->model->$propName;
		
		if (!is_array($arr)) {
			$arr = array();
		}
		
		// try to handle addAll like method...
		if (is_array($arguments[0])) {
			$arguments = $arguments[0];
		}
		
		
		$this->model->$propName = array_merge($arr, $arguments);
		$this->model->markAsDirty();
		
		return true;
	}
}
?>