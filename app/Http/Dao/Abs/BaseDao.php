<?php
/**
 * Created by PhpStorm.
 * User: t-mizuma
 * Date: 2017/10/27
 * Time: 19:22
 */
namespace App\Http\Dao\Abs;
use DB;

abstract class BaseDao {

	/** @var \Illuminate\Database\Query\Builder */
	protected $table = null;

	protected $id_name = null;

	protected $table_name = null;

	function __construct() {
		$this->init();
	}

	public function getTableName() {
		return $this->table_name;
	}

	protected function init() {
		$class_name = get_class($this);
		$this->table_name = $this->createTableName($class_name);
		$this->table = DB::table($this->table_name);
	}

	private function createTableName($class_name) {
		$spirited_array = explode("\\", str_replace('Dao', '', $class_name));
		$array = str_split(end($spirited_array));
		$table_name = '';
		$is_first = true;
		foreach($array as $char) {
			if($is_first) {
				$is_first = false;
				$table_name = $table_name . $char;
				continue;
			}
			if (ctype_upper($char)) {
				$char = '_' . $char;
			}
			$table_name = $table_name . $char;
		}
		return strtolower($table_name);
	}

	public function findAll($options = null) {
		if (empty($this->table)) {
			return null;
		}
		$sql = $this->table;
		if (empty($options) || empty($options['ignore_deleted_at'])) {
			$sql = $this->table->whereNull('deleted_at');
		}
		if (isset($options['offset']) && isset($options['limit']) ) {
			$sql->skip($options['offset'])->take($options['limit']);
		}
		return $sql->get();
	}

	public function findAllOrderByColumn($column_name, $options = null) {
		if (empty($column_name) || empty($this->table)) {
			return null;
		}
		$sql = $this->table->whereNull('deleted_at');
		if (isset($options['offset']) && isset($options['limit']) ) {
			$sql->skip($options['offset'])->take($options['limit']);
		}
		if (isset($options['order'])) {
			return $sql->get()->orderBy($column_name, $options['order']);
		}
		return $sql->orderBy($column_name, 'desc')->get();
	}

	public function findItemsById($id, $id_name = null, $options = null) {
		if (empty($this->table)) {
			return null;
		}
		$id_name = $this->getIdName($id_name);
		if (empty($id_name)) {
			return null;
		}
		$sql = $this->table
			->where($id_name, '=' , $id)
			->whereNull('deleted_at')
			->orderBy('updated_at', 'desc');

		if (isset($options['offset']) && isset($options['limit']) ) {
			$sql->skip($options['offset'])->take($options['limit']);
		}
		$ret = $sql->get();
		if (!empty($ret) && isset($ret[0]) && !empty($ret[0])) {
			return $ret;
		}
	}

	public function findById($id, $id_name = null, $options = null) {
		if (empty($this->table)) {
			return null;
		}
		$id_name = $this->getIdName($id_name);
		if (empty($id_name)) {
			return null;
		}
		$sql = $this->table
			->where($id_name, '=' , $id)
			->whereNull('deleted_at')
			->orderBy('updated_at', 'desc');

		if (isset($options['offset']) && isset($options['limit']) ) {
			$sql->skip($options['offset'])->take($options['limit']);
		}
		$ret = $sql->get();
		if (!empty($ret) && isset($ret[0]) && !empty($ret[0])) {
			return $ret[0];
		}
	}

	public function findByIds($ids, $id_name = null) {
		if (empty($this->table)) {
			return null;
		}
		$id_name = $this->getIdName($id_name);
		if (empty($id_name)) {
			return null;
		}
		return $this->table
			->whereIn($id_name, $ids)
			->whereNull('deleted_at')
			->get();
	}

	public function findWhere($where, $options = null) {
		$sql = $this->table
			->where($where)
			->whereNull('deleted_at');
		if (isset($options['offset']) && isset($options['limit']) ) {
			$sql->skip($options['offset'])->take($options['limit']);
		}
		if (isset($options['order']) && isset($options['order_column_name'])) {
			$sql->orderBy($options['order_column_name'], $options['order']);
		}
		return $sql->get();
	}

	public function isExistById($id, $id_name = null) {
		$ret = $this->findById($id, $id_name);
		return !empty($ret);
	}

	public function insert($val) {
		$this->table->insert($val);
	}

	public function insertGetId($vals) {
		return $this->table->insertGetId($vals, $this->id_name);
	}

	public function insertAndGetData($vals) {
		$id = $this->table->insertGetId($vals, $this->id_name);
		return $this->findById($id);
	}

	public function incrementById($count, $increment_column_name, $id, $id_name = null) {
		if (empty($id_name)) {
			$id_name = $this->id_name;
		}
		$this->table
			->where($id_name, $id)
			->increment($increment_column_name, $count);
	}

	public function updateById($id, $vals, $id_name = null) {
		$id_name = $this->getIdName($id_name);
		if (empty($id_name)) {
			return null;
		}
		return $this->table
			->where($id_name, $id)
			->update($vals);
	}

	public function updateByIds($ids, $vals, $id_name = null) {
		$id_name = $this->getIdName($id_name);
		if (empty($id_name)) {
			return null;
		}
		return $this->table
			->whereIn($id_name, $ids)
			->update($vals);
	}

	public function upsert($data, $keys) {
		$sql = $this->table;
		foreach ($keys as $key) {
			$sql->where($key, '=' , $data[$key]);
		}
		$ret = $data->whereNull('deleted_at')->get();
		if ( empty($ret) ) {
			return false;
		}
		return $this->insert($data);
	}

	public function deleteAndUpdateById($id, $items, $id_name = null) {
		if (empty($this->table)) {
			return null;
		}
		$id_name = $this->getIdName($id_name);
		if (empty($id_name)) {
			return null;
		}
		$this->table
			->where($id_name, $id)
			->delete();
		$this->insert($items);
	}

	public function deleteById($id, $id_name = null) {
		if (empty($this->table)) {
			return null;
		}
		$id_name = $this->getIdName($id_name);
		if (empty($id_name)) {
			return null;
		}
		return $this->table
			->where($id_name, $id)
			->delete();
	}

	public function softDelete($id, $id_name = 'id') {
		$id_name = $this->getIdName($id_name);
		if (empty($id_name)) {
			return null;
		}
		return $this->table
			->where($id_name, $id)
			->update([
				'deleted_at' => $this->getDate()
			]);
	}

	public function getIdName($id_name = null) {
		if (empty($this->id_name) && empty($id_name)) {
			return null;
		}
		if (empty($id_name)) {
			return $this->id_name;
		}
		return $id_name;
	}

	public function count() {
		return $this->table->count();
	}

	public function countByIds($where) {
		return $this->table->where($where)->count();
	}

	public function countById($id, $id_name = 'id') {
		return $this->table->where($id_name, $id)->count();
	}

	public function countNotSoftDeleted() {
		return $this->table->whereNull('deleted_at')->count();
	}

	public function countSoftDeleted() {
		return $this->table->whereNotNull('deleted_at')->count();
	}

	protected function getDate() {
		return date('Y/m/d H:i:s');
	}

	protected function doSelectSql($sql) {
		return DB::select($sql);
	}

}