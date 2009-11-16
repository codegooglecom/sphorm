<?php
class Db {
	private $pdoDb = null;
	private $connected = false;
	private $clazz;
	private $debug;

	public function __construct(DataSource $ds, $clazz, $debug = false) {
		$this->clazz = $clazz;
		$this->debug = $debug;
		try {
			$this->pdoDb = new PDO('mysql:host=' . $ds->host . ';dbname=' . $ds->schema, $ds->username, $ds->password);
			$this->connected = true;
		} catch (PDOException $ex) {
			//TODO do something here...
		}
	}

	private function executePrepared($sql, $params) {
		if (!$this->connected) {
			return null;
		}

		$this->log($sql, $params);

		$sth = $this->pdoDb->prepare($sql);
		$sth->execute($params);

		return $sth;
	}

	private function log($sql, $params = array()) {
		if ($this->debug) {
			echo '<hr>';
			echo 'Clazz: ' . $this->clazz . "<br>";
			echo 'SQL: ' . $sql . "<br>";

			if (!empty($params)) {
				echo "Params:<br>";
				foreach ($params as $key=>$val) {
					echo ' ' . $key . ': ' . $val . "<br>";
				}
			}

			echo '<hr>';
		}
	}

	public function getErrorInfo() {
		return $this->pdoDb->errorInfo();
	}

	public function getAll($sql, $params = array()) {
		$sth = $this->executePrepared($sql, $params);
		$results = $sth->fetchAll(PDO::FETCH_ASSOC);

		$objects = array();
		foreach ($results as $row) {
			$obj = new $this->clazz();
			foreach ($row as $column => $val) {
				$obj->setWithColumnCheck($column, $val);
			}
			$objects[] = $obj;
		}

		return $objects;
	}

	public function getOne($sql, $params) {
		$sth = $this->executePrepared($sql . ' LIMIT 1', $params);
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		
		$obj = new $this->clazz();
		foreach ($row as $column => $val) {
			$obj->setWithColumnCheck($column, $val);
		}
		
		return $obj;
	}

	public function getOneField($sql, $params, $field) {
		$sth = $this->executePrepared($sql . 'LIMIT 1', $params);
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		return $row[$field];
	}

	public function lastId() {
		return $this->pdoDb->lastInsertid();
	}

	public function execute($sql) {
		$this->log($sql);
		if ($this->pdoDb->exec($sql) === false) {
			$errors = $this->getErrorInfo();
			if ($this->debug && !empty($errors)) {
				echo '<hr>';
				echo 'Execution failed:' . "<br>";
				echo 'Error message: ' . $errors[2] . "<br>";
				echo 'Code: ' . $errors[1];
				echo '<hr>';
			}
			return false;
		} else {
			return true;
		}
	}

}
?>