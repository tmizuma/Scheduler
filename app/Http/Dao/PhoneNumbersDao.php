<?php

/**
 * Created by PhpStorm.
 * User: t-mizuma
 * Date: 2017/10/27
 * Time: 20:48
 */

namespace App\Http\Dao;
use App\Http\Dao\Abs\BaseDao;

class PhoneNumbersDao extends BaseDao {

	protected $id_name = 'id';

	protected $table_name = 'phone_numbers';

}