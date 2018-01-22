<?php

/**
 * Created by PhpStorm.
 * User: t-mizuma
 * Date: 2017/10/27
 * Time: 20:48
 */

namespace App\Http\Dao;
use App\Http\Dao\Abs\BaseDao;

class KeywordsDao extends BaseDao {

	protected $id_name = 'id';

	protected $table_name = 'keywords';

	/**
	 * 完全一致検索
	 */
	public function fullTextMatchSearchPhoneNumberByKeywords(array $search_keyword_array) {
		return  $this->table
			->Where(function ($query) use($search_keyword_array) {
				for ($i = 0; $i < count($search_keyword_array); $i++){
					if (empty($search_keyword_array[$i])) {
						continue;
					}
					$query->orWhere('keyword', '=',  $search_keyword_array[$i]);
				}
			})->get()->toArray();
	}

	/**
	 * 前方一致検索
	 */
	public function prefixMatchSearchPhoneNumberByKeywords(array $search_keyword_array) {
		if (empty($search_keyword_array)) {
			return [];
		}
		$sql = "select * from keywords where ";
		foreach ($search_keyword_array as $item) {
			if (empty($item)) {
				continue;
			}
			$sql = $sql .  " (keyword like '" . $item . "%') or";
		}
		$sql = substr($sql, 0, -2);
		return $this->doSelectSql($sql);
	}

	/**
	 * 後方一致検索
	 */
	public function suffixMatchSearchPhoneNumberByKeywords(array $search_keyword_array) {
		if (empty($search_keyword_array)) {
			return [];
		}
		$sql = "select * from keywords where ";
		foreach ($search_keyword_array as $item) {
			if (empty($item)) {
				continue;
			}
			$sql = $sql .  " (keyword like '" . "%" . $item . "') or";
		}
		$sql = substr($sql, 0, -2);
		return $this->doSelectSql($sql);
	}
}