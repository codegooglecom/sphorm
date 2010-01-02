<?php
class CallRemoveFrom extends AbstractCall {
	public function call() {
		$propName = $this->callName;
		$arguments = $this->callArguments;

		if (empty($arguments)) {
			return false;
		}

		if (!isset($this->model->$propName)) {
			$propName{0} = strtolower($propName{0});
		}

		$collection = $this->model->$propName;
		if (!empty($collection) && is_array($collection)) {
			$ids = $arguments;
			if (count($arguments) == 1 && is_array($arguments[0])) {
				$ids = $arguments[0];
			}

			$idsToDelete = array_flip($ids);
			foreach ($collection as $item) {
				if (isset($idsToDelete[$item->id])) {
					$item->markForDeletion();
					$this->model->setDirty();
				}
			}
			
			return true;
		}
		
		return false;
	}
}
?>