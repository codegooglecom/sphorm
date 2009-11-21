<?php
class Sphorm {
	private static $loadedClasses = array();
	private static $reflectors = array();
	private static $metaData = array();
	private $db = null;
	private $data = array();

	private $clazz;
	private $table;
	private $dirty;
	private $ds;

	// TODO make these static arrays
	private $mapping;
	private $hasOne;
	private $hasMany;

	private $errorMessages = array();

	public function __construct($init = array()) {
		$this->clazz = get_class($this);
		$this->dirty = false;
		$this->ds = DataSource::getSettings(SPHORM_ENV);

		if (!isset(self::$reflectors[$this->clazz])) {
			self::$reflectors[$this->clazz] = new ReflectionClass($this->clazz);
		}

		$this->loadStaticFields();

		if (isset($this->mapping['table'])) {
			$this->table = $this->mapping['table'];
		} else {
			$this->table = $this->clazz;
		}
			
		if (!empty($init)) {
			foreach ($init as $key => $val) {
				if (is_int($key)) {
					throw new Excetion('Illegal argument: init parameters require assoc array...');
				}
				$this->data[$key] = $val;
			}
		}

		//check if current clazz was defined as a bean
		$beansAsKeys  =  array_flip(Beans::$beans);
		if (!isset($beansAsKeys[$this->clazz])) {
			throw new Exception('Undefined bean ' . $this->clazz . '. Please check Beans.php');
		}

		$this->db = new Db($this->ds, $this->clazz, $this->table, true);

		$this->createOrUpdateDbModel();
	}

	public function __get($key) {
		if (isset($this->data[$key])) {
			return $this->data[$key];
		} else {
			$beansAsKeys  =  array_flip(Beans::$beans);

			//lazy load brother if there are any...
			if ($this->hasOne != null) {
				if (isset($this->hasOne[$key])) {
					$clazz = $this->hasOne[$key]['type'];
					if (!isset($beansAsKeys[$clazz])) {
						throw new Exception('Attempt to reference a non sphorm class (one to one). Was "' . $clazz . '" defined?.');
					}

					$this->data[$key] = $clazz()->find(array($this->hasOne[$key]['join'] => $this->id));
					return $this->data[$key];
				}
			}

			// same thing for children...
			if ($this->hasMany != null) {
				if (isset($this->hasMany[$key])) {
					$clazz = $this->hasMany[$key]['type'];
					if (!isset($beansAsKeys[$clazz])) {
						throw new Exception('Attempt to reference a non sphorm class (one to many). Was "' . $clazz . '" defined?.');
					}
					$this->data[$key] = $clazz()->findAll(array($this->hasMany[$key]['join'] => $this->id));
					return $this->data[$key];
				}
			}
		}

		return null;
	}

	public function __set($name, $value) {
		if ($this->data[$name] != $value) {
			$this->dirty = true;
			$this->data[$name] = $value;
		}
	}

	public function __toString() {
		$s = '';
		foreach ($this->data as $key => $val) {
			$s .= $key . '=' . $val . "\n";
		}
		return $s;
	}

	public function __isset($name) {
		return isset($this->data[$name]);
	}

	public function __unset($name) {
		unset($this->data[$name]);
	}

	public function __call($name, $arguments) {
		$pos = strpos($name, 'addTo');
		if ($pos === false) {
			throw new Exception('Unsupported operation: ' . $name);
		} else {
			$propName = substr($name, 5);
			//lcfirst is in 5.3
			//$propNameLower = lcfirst($propName);
			$propNameLower = $propName;
			$propNameLower{0} = strtolower($propNameLower{0});

			if (!isset($this->data[$propName]) && !isset($this->data[$propNameLower])) {
				$propName = $propNameLower;
			}

			//should load from db if needed...
			$arr = $this->$propName;
			if (!is_array($arr)) {
				$arr = array();
			}

			$this->data[$propName] = array_merge($arr, $arguments);
			$this->dirty = true;
		}
	}

	public function setWithColumnCheck($name, $value) {
		$property = $name;
		if ($name == $this->mapping['id']['column']) {
			$name = 'id';
		} else {
			foreach ($this->mapping['columns'] as $key => $val) {
				if ($val == $name) {
					$name = $key;
				}
			}
		}
		$this->data[$name] = $value;
	}


	/**
	 *		Private
	 */

	private function getColumnName($name) {
		if ($name == 'id') {
			return $this->mapping['id']['column'];
		}

		if (isset($this->mapping['columns'][$name])) {
			if (is_array($this->mapping['columns'][$name])) {
				return $this->mapping['columns'][$name]['name'];
			} else {
				return $this->mapping['columns'][$name];
			}
		}
		return $name;
	}

	private function loadStaticFields() {
		if ($this->mapping == null) {
			try {
				$this->mapping = self::$reflectors[$this->clazz]->getStaticPropertyValue('mapping');
			} catch (ReflectionException $ex) {
				throw new Exception('Sorry cannot work w/o mapping...');
			}
		}

		if ($this->hasOne == null) {
			try {
				$this->hasOne = self::$reflectors[$this->clazz]->getStaticPropertyValue('hasOne');
			} catch (ReflectionException $ex) {
				//nothing
			}
		}

		if ($this->hasMany == null) {
			try {
				$this->hasMany = self::$reflectors[$this->clazz]->getStaticPropertyValue('hasMany');
			} catch (ReflectionException $ex) {
				//nothing
			}
		}
	}

	/**
	 *		Core
	 */

	public static function init() {
		if (!empty(Beans::$beans)) {
			foreach (Beans::$beans as $clazz) {
				if (!function_exists($clazz)) {
					eval('function ' . $clazz . '() {return Sphorm::getStaticEntity("' . $clazz . '");}');
				}
			}
		} else {
			trigger_error('No beans where defined', E_USER_WARNING);
		}
	}

	public static function getStaticEntity($clazz) {
		if (!isset(self::$loadedClasses[$clazz])) {
			self::$loadedClasses[$clazz] = new $clazz();
		}

		return self::$loadedClasses[$clazz];
	}

	private function createOrUpdateDbModel() {
		if ($this->ds['dbCreate'] == 'create-drop' || $this->ds['dbCreate'] == 'create') {
			if ($this->ds['dbCreate'] == 'create-drop') {
				$this->db->dropTable();
			}

			$id = array(
				'name' => $this->mapping['id']['column']
			);

			$columns = array();
			foreach ($this->mapping['columns'] as $c) {
				if (!is_array($c)) {
					$arr = array(
					name => $c
					);
				} else {
					$arr = array(
					'name' => $c['name']
					);

					if (isset($c['type'])) {
						$arr['type'] = $c['type'];
					}

					if (isset($c['nullable'])) {
						$arr['nullable'] = $c['nullable'];
					}

					if (isset($c['default'])) {
						$arr['default'] = $c['default'];
					}
				}
				$columns[] = $arr;
			}

			$this->db->createTable($id, $columns);
		}
	}


	private function getRealColumns() {
		$columns = array();
		foreach ($this->data as $key => $val) {
			if ($key == 'id' && $this->mapping['id']['generator'] != 'auto') {
				throw new Exception('Unsupported ID generator.');
			}

			if (isset($this->hasOne[$key]) || isset($this->hasMany[$key])) {
				continue;
			}

			$columns[$this->getColumnName($key)] = $val;
		}

		return $columns;
	}

	//check if it has to fail early... otherwise will pass the ball of ignorance
	public function checkDDL() {
		$settings = Config::getSettings(SPHORM_ENV);

		if ($settings['saveStrategy'] != 'no-checking') {
			//load meta data
			if (!isset(self::$metaData[$this->clazz])) {
				self::$metaData[$this->clazz] = array();
				$meta = $this->db->getMetaData();

				foreach ($meta as $c) {
					self::$metaData[$this->clazz][$c['Field']] = $c;
				}
			}

			// will deal later with this...
			if ($settings['saveStrategy'] == 'ignore-undefined') {
				return true;
			}

			// column names are keys
			foreach ($this->getRealColumns() as $c => $val) {
				if ($settings['saveStrategy'] == 'halt-on-undefined' && !isset(self::$metaData[$this->clazz][$c])) {
					throw new Exception('Column "' . $c . '" is undefined. Stopping.');
				} else if ($settings['saveStrategy'] == 'alter-on-undefined') {
					//alter table
					throw new Exception('Alter table on demand is not implemented yet.');
				}
			}
		}

		return false;
	}


	/**
	 *		Methods
	 */

	public function save() {
		$ignore = $this->checkDDL();

		$head = 'INSERT INTO';
		$tail = '';
		if ($this->exists()) {
			if (!$this->dirty) {
				// no need to update, nothing has changed.
				return true;
			}

			$head = 'UPDATE';
			$tail = ' WHERE ' . $this->getColumnName('id') . '=' . $this->id;
		}

		$body = '';
		foreach ($this->getRealColumns() as $cName => $val) {
			if ($ignore && !isset(self::$metaData[$this->clazz][$cName])) {
//				trigger_error('Ignoring undefined column: ' . $cName, E_USER_NOTICE);
				continue;
			}

			$body .=  $cName . "='" . $val . "', ";
		}
		$body = substr($body, 0, -2);

		$myRet = $this->db->execute($head . ' ' . $this->table . ' SET ' . $body . $tail);
		if (!$myRet) {
			return $myRet;
		}

		if (empty($this->id)) {
			$this->id = $this->db->lastId();
		}

		/**
		 * cascade save
		 */

		// brothers
		if (!empty($this->hasOne) && is_array($this->hasOne)) {
			foreach ($this->hasOne as $key => $val) {
				$brother = $this->$key;

				if ($brother != null) {
					//add FK if not specified...
					$props = $brother->getColumns(true);
					if (!isset($props[$val['join']])) {
						$brother->$val['join'] = $this->id;
					}
					//TODO check if was saved...
					$brother->save();
				}
			}
		}

		// children
		if (!empty($this->hasMany) && is_array($this->hasMany)) {
			foreach ($this->hasMany as $key => $val) {
				$children = $this->$key;
				if ($children != null && is_array($children)) {
					foreach ($children as $child) {
						//add FK if not specified...
						$props = $child->getColumns(true);
						if (!isset($props[$val['join']])) {
							$child->$val['join'] = $this->id;
						}
						//TODO check if was saved...
						$child->save();
					}
				}
			}
		}

		return $myRet;
	}

	public function delete() {
		if ($this->exists()) {

			/**
			 * cascade delete
			 */
			// brothers
			if ($this->hasOne != null) {
				foreach ($this->hasOne as $key => $val) {
					$brother = $this->$key;
					if ($brother != null) {
						//add FK if not specified...
						$props = $brother->getColumns(true);
						if (!isset($props[$val['join']])) {
							$brother->$val['join'] = $this->id;
						}
						$brother->delete();
					}
				}
			}

			//children
			if ($this->hasMany != null) {
				foreach ($this->hasMany as $key => $val) {
					$children = $this->$key;
					if ($children != null && is_array($children)) {
						foreach ($children as $child) {
							//add FK if not specified...
							$props = $child->getColumns(true);
							if (!isset($props[$val['join']])) {
								$child->$val['join'] = $this->id;
							}
							//TODO check if was saved...
							$child->delete();
						}
					}
				}
			}


			$sql = 'DELETE FROM ' . $this->table . ' WHERE ' . $this->getColumnName('id') . '=' . $this->id;
			return $this->db->execute($sql);
		}

		return false;
	}

	public function exists() {
		if (!isset($this->id)) {
			return false;
		}

		$obj = $this->get($this->id);
		if (empty($obj)) {
			return false;
		}
		return true;
	}

	public function getColumns($flip = false) {
		if ($flip){
			return array_flip($this->mapping['columns']);
		} else {
			return $this->mapping['columns'];
		}
	}


	/**
	 *		'static' methods
	 */

	public function count() {
		$sql = 'SELECT COUNT(*) as Total FROM ' . $this->table;
		return $this->db->getOneField($sql, array(), 'Total');
	}

	public function get() {
		$params = func_get_args();

		if (count($params) == 1 && is_array(func_get_arg(0))) {
			$params = func_get_arg(0);
		}

		if (empty($params)) {
			return $this->all();
		}

		$n = count($params);
		$qs = str_repeat("?,", $n - 1) . '?';
		$sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->mapping['id']['column'] . ' IN (' . $qs . ')';
		$recs = $this->db->getAll($sql, $params);

		if (count($recs) == 1 && $n == 1) {
			return $recs[0];
		} else {
			if (empty($recs)) {
				return null;
			} else {
				return $recs;
			}
		}
	}

	public function all($params = array()) {
		$sql = 'SELECT * FROM ' . $this->table;

		if (isset($params['order'])) {
			$sql .= ' ORDER BY ';
			if (is_array($params['order'])) {
				$arr = array();
				foreach ($params['order'] as $dir => $c) {
					if (strcasecmp($dir, 'desc') == 0 || strcasecmp($dir, 'asc') == 0) {
						$arr[] = $this->getColumnName($c) . ' ' . strtoupper($dir);
					} else {
						$arr[] = $this->getColumnName($c);
					}
				}
				$sql .= implode(',', $arr);
			} else {
				$sql .= $this->getColumnName($params['order']);
			}
		}

		if (isset($params['max'])) {
			$limit = (int)$params['max'];
			if (isset($params['offset'])) {
				$limit = (int)$params['offset'] . ',' . $limit;
			}

			//TODO in future make usage of an dialect
			$sql .= ' LIMIT ' . $limit;
		}
		return $this->db->getAll($sql);
	}

	//used by other find* methods
	private function findGeneric(array $params, $all, $operator = '=') {
		if (empty($params)) {
			return null;
		}

		$sql = 'SELECT * FROM ' . $this->table . ' WHERE ';

		$finalParams = array();
		foreach ($params as $key => $val) {
			if (is_array($val)) {
				throw new Exception('Multiple values not supported yet.');
			}
			$sql .= $this->getColumnName($key) . $operator . "? AND ";
			$finalParams[] = $val;
		}

		$sql = substr($sql, 0, -5);
		if ($all) {
			return $this->db->getAll($sql, $finalParams);
		} else {
			return $this->db->getOne($sql, $finalParams);
		}
	}

	//1st match
	public function find(array $params) {
		return $this->findGeneric($params, false);
	}

	//all matches
	public function findAll(array $params) {
		return $this->findGeneric($params, true);
	}
}
