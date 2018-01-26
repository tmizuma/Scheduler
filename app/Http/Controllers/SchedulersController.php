<?php

namespace App\Http\Controllers;
use App\Http\Service\SchedulersService;
use App\Http\Component\StatusCode;
use App\Scheduler;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SchedulersController extends AjaxController {

    /** @var Request  */
    private $request;
    /** @var SchedulersService  */
    private $schedulersService;
    
    public function __construct( SchedulersService $SchedulersService, Request $request ) {
        $this->request = $request;
        $this->schedulersService = $SchedulersService;
    }

    public function index() {
        $this->validate($this->request,[
            'target_date' 	=> ['string'],
            'room_id' 	=> ['integer'],
        ]);
        $target_date = $this->request->input('target_date');
        $room_id = $this->request->input('room_id');
        if (empty($target_date)) {
            $this->data = $this->schedulersService->findSchedulers();
        } else {
            $this->data = $this->schedulersService->findTargetSchedulersByTargetDateAndRoomId($target_date,  $room_id);
        }
        return $this->response();
    }

    public function show($id) {
        $this->data = $this->schedulersService->findById($id);
        return $this->response();
    }

    public function store() {
        $this->validate($this->request,[
            'user_name' 	=> ['string'],
            'start_time' 	=> ['string','required'],
            'end_time' 	    => ['string','required'],
            'room_id' 		=> ['integer','required']
        ]);
        if (
            $this->schedulersService->isDuplicate(
                $this->request->input('room_id'),
                $this->request->input('start_time'),
                $this->request->input('end_time')
            )
        ) {
            $this->status = StatusCode::DUPLICATE_SCHEDULE;
            return $this->response();
        }
        $this->data = $this->schedulersService->createScheduler([
            'room_id' => $this->request->input('room_id'),
            'user_name' => $this->request->input('user_name'),
            'start_time' => $this->request->input('start_time'),

            'end_time' => $this->request->input('end_time'),
            'description' => empty($this->request->input('description')) ? '' : $this->request->input('description'),
            'updated_at' => Carbon::now()
        ]);
    }

    public function update($id) {
        $this->validate($this->request,[
            'user_name' 	=> ['string'],
            'start_time' 	=> ['string','required'],
            'end_time' 	    => ['string','required'],
            'room_id' 		=> ['integer','required'],
        ]);
        if (
        $this->schedulersService->isDuplicate(
            $this->request->input('room_id'),
            $this->request->input('start_time'),
            $this->request->input('end_time'),
            $id
        )
        ) {
            $this->status = StatusCode::DUPLICATE_SCHEDULE;
            return $this->response();
        }
        $this->schedulersService->updateScheduler($id, [
            'room_id' => $this->request->input('room_id'),
            'user_name' => $this->request->input('user_name'),
            'duplicate_check' => Scheduler::calculateDuplicateCheckVal(substr($this->request->input('start_time'),11,5), substr($this->request->input('end_time'),11,5)),
            'start_time' => $this->request->input('start_time'),
            'end_time' => $this->request->input('end_time'),
            'description' => empty($this->request->input('description')) ? '' : $this->request->input('description'),
            'updated_at' => Carbon::now()
        ]);
    }

    public function destroy($id) {
        $this->schedulersService->deleteScheduler($id);
    }
}
