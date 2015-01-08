<?php
if(!defined('_CorePath_')){
	exit;
}

/*!
 * Medoo database framework
 * http://medoo.in
 * Version 0.8.8
 * 
 * Copyright 2013, Angel Lai
 * Released under the MIT license
 */

class medoo implements CLib\SqlInterface{
	protected $database_type = 'mysql';

	// For MySQL, MSSQL, Sybase
	protected $server = 'localhost';

	protected $username = 'username';

	protected $password = 'password';

	// For SQLite
	protected $database_file = '';

	// Optional
	protected $port = 3306;

	protected $charset = 'utf8';

	protected $database_name = '';

	protected $option = array();

	protected $count_number;

	protected $queryString;

	public function __construct($options){
		$this->count_number = 0;
		try{
			$commands = array();
			if(is_string($options)){
				if(strtolower($this->database_type) == 'sqlite'){
					$this->database_file = $options;
				} else{
					$this->database_name = $options;
				}
			} else{
				foreach($options as $option => $value){
					$this->$option = $value;
				}
			}
			$type = strtolower($this->database_type);
			if(isset($this->port) && is_int($this->port * 1)
			){
				$port = $this->port;
			}
			$set_charset = "SET NAMES '" . $this->charset . "'";
			switch($type){
				case 'mariadb':
					$type = 'mysql';
				case 'mysql':
					// Make MySQL using standard quoted identifier
					$commands[] = 'SET SQL_MODE=ANSI_QUOTES';
				case 'pgsql':
					$dsn = $type . ':host=' . $this->server . (isset($port) ? ';port=' . $port : '') . ';dbname=' . $this->database_name;
					$commands[] = $set_charset;
					break;
				case 'sybase':
					$dsn = $type . ':host=' . $this->server . (isset($port) ? ',' . $port : '') . ';dbname=' . $this->database_name;
					$commands[] = $set_charset;
					break;
				case 'mssql':
					$dsn = strpos(PHP_OS, 'WIN') !== false ? 'sqlsrv:server=' . $this->server . (isset($port) ? ',' . $port : '') . ';database=' . $this->database_name : 'dblib:host=' . $this->server . (isset($port) ? ':' . $port : '') . ';dbname=' . $this->database_name;
					// Keep MSSQL QUOTED_IDENTIFIER is ON for standard quoting
					$commands[] = 'SET QUOTED_IDENTIFIER ON';
					$commands[] = $set_charset;
					break;
				case 'sqlite':
					$dsn = $type . ':' . $this->database_file;
					$this->username = NULL;
					$this->password = NULL;
					break;
				default:
					throw new Exception(_("Type:") . $type . _("not defined"));
			}
			$this->pdo = new PDO($dsn, $this->username, $this->password, $this->option);
			foreach($commands as $value){
				$this->pdo->exec($value);
			}
		} catch(PDOException $e){
			throw new Exception($e->getMessage());
		}
	}

	public function get_query_count(){
		return $this->count_number;
	}

	public function query($query){
		$this->queryString = $query;
		$this->count_number++;
		return $this->pdo->query($query);
	}

	public function exec($query){
		$this->queryString = $query;
		$this->count_number++;
		return $this->pdo->exec($query);
	}

	/**
	 * @param string $statement
	 * @param array  $driver_options
	 * @return PDOStatement
	 */
	public function prepare($statement, $driver_options = array()){
		$this->count_number++;
		$this->queryString = $statement;
		return $this->pdo->prepare($statement, $driver_options);
	}

	public function quote($string){
		return $this->pdo->quote($string);
	}

	protected function column_quote($string){
		return '`' . str_replace('.', '`.`', $string) . '`';
	}

	protected function array_quote($array){
		$temp = array();

		foreach($array as $value){
			$temp[] = is_int($value) ? $value : $this->pdo->quote($value);
		}

		return implode($temp, ',');
	}

	protected function inner_conjunct($data, $conjunctor, $outer_conjunctor){
		$haystack = array();

		foreach($data as $value){
			$haystack[] = '(' . $this->data_implode($value, $conjunctor) . ')';
		}

		return implode($outer_conjunctor . ' ', $haystack);
	}

	protected function data_implode($data, $conjunctor, $outer_conjunctor = NULL){
		$wheres = array();

		foreach($data as $key => $value){
			if(($key == 'AND' || $key == 'OR') && is_array($value)
			){
				$wheres[] = 0 !== count(array_diff_key($value, array_keys(array_keys($value)))) ? '(' . $this->data_implode($value, ' ' . $key) . ')' : '(' . $this->inner_conjunct($value, ' ' . $key, $conjunctor) . ')';
			} else if($key === 'LIKE' && is_array($value)){
				$wheres[] = substr($this->where_clause(['LIKE' => $value]), 7);
			} else{
				preg_match('/([\w\.]+)(\[(\>|\>\=|\<|\<\=|\!|\<\>)\])?/i', $key, $match);
				if(isset($match[3])){
					if($match[3] == ''){
						$wheres[] = $this->column_quote($match[1]) . ' ' . $match[3] . '= ' . $this->quote($value);
					} elseif($match[3] == '!'){
						$column = $this->column_quote($match[1]);

						switch(gettype($value)){
							case 'NULL':
								$wheres[] = $column . ' IS NOT NULL';
								break;

							case 'array':
								$wheres[] = $column . ' NOT IN (' . $this->array_quote($value) . ')';
								break;

							case 'integer':
							case 'double':
								$wheres[] = $column . ' != ' . $value;
								break;

							case 'string':
								$wheres[] = $column . ' != ' . $this->quote($value);
								break;
						}
					} else{
						if($match[3] == '<>'){
							if(is_array($value)){
								if(is_numeric($value[0]) && is_numeric($value[1])){
									$wheres[] = $this->column_quote($match[1]) . ' BETWEEN ' . $value[0] . ' AND ' . $value[1];
								} else{
									$wheres[] = $this->column_quote($match[1]) . ' BETWEEN ' . $this->quote($value[0]) . ' AND ' . $this->quote($value[1]);
								}
							}
						} else{
							if(is_numeric($value)){
								$wheres[] = $this->column_quote($match[1]) . ' ' . $match[3] . ' ' . $value;
							} else{
								$datetime = strtotime($value);

								if($datetime){
									$wheres[] = $this->column_quote($match[1]) . ' ' . $match[3] . ' ' . $this->quote(date('Y-m-d H:i:s', $datetime));
								}
							}
						}
					}
				} else{
					if(is_int($key)){
						$wheres[] = $this->quote($value);
					} else{
						$column = $this->column_quote($match[1]);

						switch(gettype($value)){
							case 'NULL':
								$wheres[] = $column . ' IS NULL';
								break;

							case 'array':
								$wheres[] = $column . ' IN (' . $this->array_quote($value) . ')';
								break;

							case 'integer':
							case 'double':
								$wheres[] = $column . ' = ' . $value;
								break;

							case 'string':
								$wheres[] = $column . ' = ' . $this->quote($value);
								break;
						}
					}
				}
			}
		}
		return implode($conjunctor . ' ', $wheres);
	}

	public function where_clause($where){
		$where_clause = '';

		if(is_array($where)){
			$single_condition = array_diff_key($where, array_flip(explode(' ', 'AND OR GROUP ORDER HAVING LIMIT LIKE MATCH')));

			if($single_condition != array()){
				$where_clause = ' WHERE ' . $this->data_implode($single_condition, '');
			}
			if(isset($where['AND'])){
				$where_clause = ' WHERE ' . $this->data_implode($where['AND'], ' AND');
			}
			if(isset($where['OR'])){
				$where_clause = ' WHERE ' . $this->data_implode($where['OR'], ' OR');
			}
			if(isset($where['LIKE'])){
				$like_query = $where['LIKE'];
				if(is_array($like_query)){
					$is_OR = isset($like_query['OR']);

					if($is_OR || isset($like_query['AND'])){
						$connector = $is_OR ? 'OR' : 'AND';
						$like_query = $is_OR ? $like_query['OR'] : $like_query['AND'];
					} else{
						$connector = 'AND';
					}

					$clause_wrap = array();
					foreach($like_query as $column => $keyword){
						if(is_array($keyword)){
							foreach($keyword as $key){
								$clause_wrap[] = $this->column_quote($column) . ' LIKE ' . $this->quote('%' . $key . '%');
							}
						} else{
							$clause_wrap[] = $this->column_quote($column) . ' LIKE ' . $this->quote('%' . $keyword . '%');
						}
					}
					$where_clause .= ($where_clause != '' ? ' AND ' : ' WHERE ') . '(' . implode($clause_wrap, ' ' . $connector . ' ') . ')';
				}
			}
			if(isset($where['MATCH'])){
				$match_query = $where['MATCH'];
				if(is_array($match_query) && isset($match_query['columns']) && isset($match_query['keyword'])){
					$where_clause .= ($where_clause != '' ? ' AND ' : ' WHERE ') . ' MATCH (`' . str_replace('.', '`.`', implode($match_query['columns'], '`, `')) . '`) AGAINST (' . $this->quote($match_query['keyword']) . ')';
				}
			}
			if(isset($where['GROUP'])){
				$where_clause .= ' GROUP BY ' . $this->column_quote($where['GROUP']);
			}
			if(isset($where['ORDER'])){
				preg_match('/(^[a-zA-Z0-9_\-\.]*)(\s*(DESC|ASC))?/', $where['ORDER'], $order_match);

				$where_clause .= ' ORDER BY `' . str_replace('.', '`.`', $order_match[1]) . '` ' . (isset($order_match[3]) ? $order_match[3] : '');

				if(isset($where['HAVING'])){
					$where_clause .= ' HAVING ' . $this->data_implode($where['HAVING'], '');
				}
			}
			if(isset($where['LIMIT'])){
				if(is_numeric($where['LIMIT'])){
					$where_clause .= ' LIMIT ' . $where['LIMIT'];
				}
				if(is_array($where['LIMIT']) && is_numeric($where['LIMIT'][0]) && is_numeric($where['LIMIT'][1])
				){
					$where_clause .= ' LIMIT ' . $where['LIMIT'][0] . ',' . $where['LIMIT'][1];
				}
			}
		} else{
			if($where != NULL){
				$where_clause .= ' ' . $where;
			}
		}

		return $where_clause;
	}

	public function select($table, $join, $columns = NULL, $where = NULL){
		$table = '`' . $table . '`';

		if($where){
			$table_join = array();

			$join_array = array(
				'>' => 'LEFT',
				'<' => 'RIGHT',
				'<>' => 'FULL',
				'><' => 'INNER'
			);

			foreach($join as $sub_table => $relation){
				preg_match('/(\[(\<|\>|\>\<|\<\>)\])?([a-zA-Z0-9_\-]*)/', $sub_table, $match);

				if($match[2] != '' && $match[3] != ''){
					if(is_string($relation)){
						$relation = 'USING (`' . $relation . '`)';
					}

					if(is_array($relation)){
						// For ['column1', 'column2']
						if(isset($relation[0])){
							$relation = 'USING (`' . implode($relation, '`, `') . '`)';
						} // For ['column1' => 'column2']
						else{
							$__relation = 'ON ' . $table . '.`' . key($relation) . '` = `' . $match[3] . '`.`' . current($relation) . '`';
							//添加部分特殊操作，当这种情况下，$relation 有多种关系时，第二个参数使用WHERE语句进行二次递归解析
							if(count($relation) === 2){
								$_re_where = array_pop($relation);
								$__relation .= " AND ( " . $this->data_implode($_re_where, '') . " )";
							}
							$relation = $__relation;
						}
					}

					$table_join[] = $join_array[$match[2]] . ' JOIN `' . $match[3] . '` ' . $relation;
				}
			}

			$table .= ' ' . implode($table_join, ' ');
		} else{
			$where = $columns;
			$columns = $join;
		}

		$where_clause = $this->where_clause($where);

		$query = $this->query('SELECT ' . (is_array($columns) ? $this->column_alias($columns) : ($columns == '*' ? '*' : '`' . $columns . '`')) . ' FROM ' . $table . $where_clause);

		return $query ? $query->fetchAll((is_string($columns) && $columns != '*') ? PDO::FETCH_COLUMN : PDO::FETCH_ASSOC) : false;
	}

	private function column_alias($arr){
		$rt = array();
		if(is_array($arr)){
			foreach($arr as $name => $alias){
				if(is_int($name)){
					$rt[] = $this->column_quote($alias);
				} else{
					$rt[] = $this->column_quote($name) . " as " . $this->column_quote($alias);
				}
			}
			return implode(", ", $rt);
		} else{
			return $this->column_quote($arr);
		}
	}

	public function insert($table, $data){
		$all = [];
		for($i = 1, $l = func_num_args(); $i < $l; $i++){
			$data = func_get_arg($i);
			$keys = implode("`, `", array_keys($data));
			$values = array();

			foreach($data as $key => $value){
				switch(gettype($value)){
					case 'NULL':
						$values[] = 'NULL';
						break;

					case 'array':
						$values[] = $this->quote(serialize($value));
						break;

					case 'integer':
					case 'double':
					case 'string':
						$values[] = $this->quote($value);
						break;
					default:
						$values[] = $this->quote($value);
						break;
				}
			}
			$all[] = '(' . implode($values, ', ') . ')';
		}


		$rt = $this->exec('INSERT INTO `' . $table . '` (`' . $keys . '`) VALUES ' . implode(", ", $all));
		if($rt === false){
			return -1;
		}
		return intval($this->pdo->lastInsertId());
	}

	public function update($table, $data, $where = NULL){
		$fields = array();

		foreach($data as $key => $value){
			if(is_array($value)){
				$fields[] = $this->column_quote($key) . ' = ' . $this->quote(serialize($value));
			} else{
				preg_match('/([\w]+)(\[(\+|\-|\*|\/)\])?/i', $key, $match);
				if(isset($match[3])){
					if(is_numeric($value)){
						$fields[] = $this->column_quote($match[1]) . ' = ' . $this->column_quote($match[1]) . ' ' . $match[3] . ' ' . $value;
					}
				} else{
					$column = $this->column_quote($key);

					switch(gettype($value)){
						case 'NULL':
							$fields[] = $column . ' = NULL';
							break;
						case 'array':
							$fields[] = $column . ' = ' . $this->quote(serialize($value));
							break;
						case 'integer':
						case 'double':
						case 'string':
							$fields[] = $column . ' = ' . $this->quote($value);
							break;
					}
				}
			}
		}

		return $this->exec('UPDATE `' . $table . '` SET ' . implode(', ', $fields) . $this->where_clause($where));
	}

	public function delete($table, $where){
		return $this->exec('DELETE FROM `' . $table . '`' . $this->where_clause($where));
	}

	public function replace($table, $columns, $search = NULL, $replace = NULL, $where = NULL){
		if(is_array($columns)){
			$replace_query = array();

			foreach($columns as $column => $replacements){
				foreach($replacements as $replace_search => $replace_replacement){
					$replace_query[] = $column . ' = REPLACE(`' . $column . '`, ' . $this->quote($replace_search) . ', ' . $this->quote($replace_replacement) . ')';
				}
			}
			$replace_query = implode(', ', $replace_query);
			$where = $search;
		} else{
			if(is_array($search)){
				$replace_query = array();

				foreach($search as $replace_search => $replace_replacement){
					$replace_query[] = $columns . ' = REPLACE(`' . $columns . '`, ' . $this->quote($replace_search) . ', ' . $this->quote($replace_replacement) . ')';
				}
				$replace_query = implode(', ', $replace_query);
				$where = $replace;
			} else{
				$replace_query = $columns . ' = REPLACE(`' . $columns . '`, ' . $this->quote($search) . ', ' . $this->quote($replace) . ')';
			}
		}

		return $this->exec('UPDATE `' . $table . '` SET ' . $replace_query . $this->where_clause($where));
	}

	public function get($table, $columns, $where = NULL){
		if(!isset($where)){
			$where = array();
		}
		$where['LIMIT'] = 1;

		$data = $this->select($table, $columns, $where);

		return isset($data[0]) ? $data[0] : false;
	}

	public function has($table, $where){
		return $this->query('SELECT EXISTS(SELECT 1 FROM `' . $table . '`' . $this->where_clause($where) . ')')->fetchColumn() === '1';
	}

	public function count($table, $where = NULL){
		return 0 + ($this->query('SELECT COUNT(*) FROM `' . $table . '`' . $this->where_clause($where))->fetchColumn());
	}

	public function max($table, $column, $where = NULL){
		return 0 + ($this->query('SELECT MAX(`' . $column . '`) FROM `' . $table . '`' . $this->where_clause($where))->fetchColumn());
	}

	public function min($table, $column, $where = NULL){
		return 0 + ($this->query('SELECT MIN(`' . $column . '`) FROM `' . $table . '`' . $this->where_clause($where))->fetchColumn());
	}

	public function avg($table, $column, $where = NULL){
		return 0 + ($this->query('SELECT AVG(`' . $column . '`) FROM `' . $table . '`' . $this->where_clause($where))->fetchColumn());
	}

	public function sum($table, $column, $where = NULL){
		return 0 + ($this->query('SELECT SUM(`' . $column . '`) FROM `' . $table . '`' . $this->where_clause($where))->fetchColumn());
	}

	public function error(){
		return $this->pdo->errorInfo();
	}

	public function last_query(){
		return $this->queryString;
	}

	public function info(){
		return array(
			'server' => $this->pdo->getAttribute(PDO::ATTR_SERVER_INFO),
			'client' => $this->pdo->getAttribute(PDO::ATTR_CLIENT_VERSION),
			'driver' => $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME),
			'version' => $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION),
			'connection' => $this->pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS)
		);
	}
}

?>