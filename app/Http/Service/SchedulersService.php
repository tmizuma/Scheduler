<?php

/**
 * Created by PhpStorm.
 * User: t-mizuma
 * Date: 2017/12/04
 * Time: 20:43
 */

namespace App\Http\Service;
use App\Http\Dao\SchedulersDao;
use App\Scheduler;
use Carbon\Carbon;

class SchedulersService {

	/** @var SchedulersDao */
	private $schedulersDao;

	public function __construct( SchedulersDao $SchedulersDao ) {
		$this->schedulersDao = $SchedulersDao;
	}

	public function findSchedulers( $target_date = null ) {
		if (empty($target_date)) {
			$target_date = date('Y-m-d');
		}
		return $this->schedulersDao->findTargetSchedulers($target_date);
	}

	public function findTargetSchedulersByTargetDateAndRoomId( $target_date, $room_id) {
		return $this->schedulersDao->findTargetSchedulersByTargetDateAndRoomId($target_date, $room_id);
	}

	public function isStartTimeDuplicate($room_id, $start_time, $end_time, $id = null) {
		return $this->schedulersDao->isStartTimeDuplicate($room_id, $start_time, $end_time, $id);
	}

	public function isEndTimeDuplicate($room_id, $start_time, $end_time, $id = null) {
		return $this->schedulersDao->isEndTimeDuplicate($room_id, $start_time, $end_time, $id);
	}

	public function findById($id) {
		return $this->schedulersDao->findById($id);
	}

	public function createScheduler( $data ) {
		$scheduler = new Scheduler();
		$scheduler->user_name = $data['user_name'];
		$scheduler->room_id = $data['room_id'];
		$scheduler->description = $data['description'];
		$scheduler->start_time = $data['start_time'];
		$scheduler->end_time = $data['end_time'];
		$scheduler->updated_at = Carbon::now();
		$scheduler->save();
		return $scheduler;
	}

	public function updateScheduler( $id, $data ) {
		return Scheduler::where('id', $id)
			->update($data);
	}

	public function deleteScheduler($id) {
		$this->schedulersDao->softDelete($id);
	}

}