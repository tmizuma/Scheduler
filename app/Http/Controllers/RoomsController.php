<?php

namespace App\Http\Controllers;
use App\Http\Service\SchedulersService;
use App\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomsController extends AjaxController {

    public function index() {
        $this->data = Room::all();
        return $this->response();
    }
}
