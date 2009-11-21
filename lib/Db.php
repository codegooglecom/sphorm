<?php
class Db {
	private $pdoDb = null;
	private $connected = false;
	private $clazz;
	private $table;
	private $debug;

	public function __construct($ds, $clazz, $table, $debug = false) {
		$this->clazz = $clazz;
		$this->table = $table;
		$this->debug = $debug;
		try {
			$this->pdoDb = new PDO($ds['engine'] . ':host=' . $ds['host'] . ';dbname=' . $ds['schema'], $ds['username'], $ds['password']);
			$this->connected = true;
		} catch (PDOException $ex) {
			throw new Exception('Could not connect to database.');
		}
	}

	private function executePrepared($sql, $params = array()) {
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
		
		if (empty($row)) {
			return null;
		}
		
		$obj = new $this->clazz();
		foreach ($row as $column => $val) {
			$obj->setWithColumnCheck($column, $val);
		}
		
		return $obj;
	}

	public function getOneField($sql, $params, $field) {
		$sth = $this->executePrepared($sql . ' LIMIT 1', $params);
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
	
	public function dropTable() {
		return $this->execute('DROP TABLE IF EXISTS ' . $this->table);
	}
	
	public function createTable(array $id, array $columns, array $index = array()) {
		if (empty($columns) || empty($id)) {
			trigger_error('id or columns data is empty, cannot create table.', E_USER_WARNING);
			return;
		}
	
		$sql = 'CREATE TABLE ' . $this->table . ' (';
		$sql .= $id['name'] . ' int(11) NOT NULL AUTO_INCREMENT,';
		
		foreach ($columns as $c) {
			if (!isset($c['name'])) {
				throw new Exception('No column name specified, cannot create table.');
			}
			
			$type = 'VARCHAR(255)';
			$nullable = '';
			$default = '';
			
			if (isset($c['type'])) {
				$type = $c['type'];
			}
			
			if (isset($c['nullable'])) {
				if ($c['nullable'] === false) {
					$nullable = 'NOT NULL';
				} 
			}
			
			if (isset($c['default'])) {
				$default = $c['default'];
			}
			
			//check for int's default value
			
			$sql .= $c['name'] . ' ' . $type . ' ' . $nullable . ' DEFAULT ' . "'" . $default . "',";
		}
		$sql .= 'PRIMARY KEY (' . $id['name'] .')';
		$sql .= ') ENGINE=MyISAM DEFAULT CHARSET=utf8';
		
		return $this->execute($sql);
	}
	
	public function getMetaData() {
		$sql = 'SHOW COLUMNS FROM ' . $this->table;
		$sth = $this->executePrepared($sql);
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
}
?>