<?php
class CallFindAllBy extends AbstractCall {
	public function call() {
		$propName = $this->callName;
		$arguments = $this->callArguments;

		$props = explode('And', $propName);

		$n = count($props);
		$params = array();
		for ($i = 0; $i < $n; $i++) {
			if (empty($props[$i])) {
				continue;
			}
				
			$prop = $props[$i];
			$skip = false;
				
			if (!$this->model->propertyIsMapped($prop)) {
				$prop{0} = strtolower($prop{0});

				if (!$this->model->propertyIsMapped($prop)) {
					$skip = true;
				}
			}
			if (!$skip) {
				$params[$prop] = $arguments[$i];
			}
		}


		return $this->model->findAll($params);
	}
}
?>