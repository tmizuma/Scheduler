<?php

/**
 * Created by PhpStorm.
 * User: t-mizuma
 * Date: 2017/10/27
 * Time: 20:48
 */

namespace App\Http\Dao;
use App\Http\Dao\Abs\BaseDao;

class CustomerMailsDao extends BaseDao {

	protected $table_name = 'customer_mails';

	public function updateLastAccessDate( $customer_id, $last_access_date ) {
		if ( empty($customer_id) || empty($last_access_date) ) {
			return [];
		}
		return $this->updateById($customer_id , array('customer_check_date' => $last_access_date) , 'customer_id');
	}
}