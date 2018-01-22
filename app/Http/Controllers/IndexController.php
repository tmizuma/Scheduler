<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class IndexController extends BaseController {

    public function __construct() {
     }
    
    public function index() {
        return view('index')->with(['title' => '会議室予約']);
    }

}
