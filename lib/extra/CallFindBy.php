<?php
class CallFindBy extends AbstractCall {
	public function call() {
		$propName = $this->callName;
		$arguments = $this->callArguments;

		$props = explode('And', $propName);

		if (count($props) != count($arguments)) {
			throw new Exception('Number of properties and values provided don\'t match.');
		}

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


		return $this->model->find($params);
	}
}
?>