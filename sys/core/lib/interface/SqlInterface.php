<?php
namespace CLib;

/**
 * 数据库连接
 */
interface SqlInterface{
	/**
	 * @param $query
	 * @return mixed
	 */
	public function query($query);

	/**
	 * @param $query
	 * @return mixed
	 */
	public function exec($query);

	/**
	 * @param $string
	 * @return mixed
	 */
	public function quote($string);

	/**
	 * @param $where
	 * @return mixed
	 */
	public function where_clause($where);

	/**
	 * @param      $table
	 * @param      $join
	 * @param null $columns
	 * @param null $where
	 * @return mixed
	 */
	public function select($table, $join, $columns = NULL, $where = NULL);

	/**
	 * @param $table
	 * @param $data
	 * @return mixed
	 */
	public function insert($table, $data);

	/**
	 * @param      $table
	 * @param      $data
	 * @param null $where
	 * @return mixed
	 */
	public function update($table, $data, $where = NULL);

	/**
	 * @param $table
	 * @param $where
	 * @return mixed
	 */
	public function delete($table, $where);

	/**
	 * @param      $table
	 * @param      $columns
	 * @param null $search
	 * @param null $replace
	 * @param null $where
	 * @return mixed
	 */
	public function replace($table, $columns, $search = NULL, $replace = NULL, $where = NULL);

	/**
	 * @param      $table
	 * @param      $columns
	 * @param null $where
	 * @return mixed
	 */
	public function get($table, $columns, $where = NULL);

	/**
	 * @param $table
	 * @param $where
	 * @return mixed
	 */
	public function has($table, $where);

	/**
	 * @param      $table
	 * @param null $where
	 * @return mixed
	 */
	public function count($table, $where = NULL);

	/**
	 * @param      $table
	 * @param      $column
	 * @param null $where
	 * @return mixed
	 */
	public function max($table, $column, $where = NULL);

	/**
	 * @param      $table
	 * @param      $column
	 * @param null $where
	 * @return mixed
	 */
	public function min($table, $column, $where = NULL);

	/**
	 * @param      $table
	 * @param      $column
	 * @param null $where
	 * @return mixed
	 */
	public function avg($table, $column, $where = NULL);

	/**
	 * @param      $table
	 * @param      $column
	 * @param null $where
	 * @return mixed
	 */
	public function sum($table, $column, $where = NULL);

	/**
	 * @return mixed
	 */
	public function error();

	/**
	 * @return mixed
	 */
	public function last_query();

	/**
	 * @return mixed
	 */
	public function info();

	/**
	 * @return mixed
	 */
	public function get_query_count();
}