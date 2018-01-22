<?php

namespace App\Http\Controllers;
use App\Http\Service\SchedulersService;
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
        $yyyy = $this->request->input('yyyy');
        $mm = $this->request->input('mm');
        $dd = $this->request->input('dd');
        $target_date = $yyyy . '-' . $mm . '-' . $dd;
        if ($target_date == '--') {
            $this->data = $this->schedulersService->findSchedulers();
        } else {
            $this->data = $this->schedulersService->findTargetSchedulersByTargetDateAndRoomId($target_date,  $this->request->input('room_id'));
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
        if ($this->schedulersService->isDuplicate(
            $this->request->input('room_id'),
            $this->request->input('start_time'),
            $this->request->input('end_time')
        )) {
            $this->data = null;
        } else {
            $this->data = $this->schedulersService->createScheduler([
                'room_id' => $this->request->input('room_id'),
                'user_name' => $this->request->input('user_name'),
                'start_time' => $this->request->input('start_time'),
                'end_time' => $this->request->input('end_time'),
                'description' => empty($this->request->input('description')) ? '' : $this->request->input('description'),
                'updated_at' => Carbon::now()
            ]);
        }
    }

    public function update($id) {
        $this->validate($this->request,[
            'user_name' 	=> ['string'],
            'start_time' 	=> ['string','required'],
            'end_time' 	    => ['string','required'],
            'room_id' 		=> ['integer','required']
        ]);
        if ($this->schedulersService->isDuplicate(
            $this->request->input('room_id'),
            $this->request->input('start_time'),
            $this->request->input('end_time'),
            $id
        )) {
            $this->data = null;
        } else {
            $this->schedulersService->updateScheduler($id, [
                'room_id' => $this->request->input('room_id'),
                'user_name' => $this->request->input('user_name'),
                'start_time' => $this->request->input('start_time'),
                'end_time' => $this->request->input('end_time'),
                'description' => empty($this->request->input('description')) ? '' : $this->request->input('description'),
                'updated_at' => Carbon::now()
            ]);
        }
    }

    public function destroy($id) {
        $this->schedulersService->deleteScheduler($id);
    }
}
