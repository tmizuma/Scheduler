<?php

namespace App\Http\Controllers;

use App\Http\Component\StatusCode;
use \Illuminate\Support\Facades\Auth;

/**
 * Class AjaxController
 * @package App\Http\Controllers\Api
 *
 * AjaxでアクセスされるControllerの基底クラス
 * Ajaxレスポンスは以下のフォーマットで返却される
 *
 * 	{
 * 	 	status : #ajax status code,
 *  	error  : { #error message },
 *  	data   : { #response object }
 *  }
 */

class AjaxController extends Controller {
	
	protected $status = StatusCode::SUCCESS;
	protected $error  = null;
	protected $data   = null;

	protected function response() {
		return response()->json([
			'status' => $this->status,
			'error'  => $this->error,
			'data'	 => $this->data,
			'session' => $this->getMasterData()
		], $this->status);
	}

	protected function isSmartPhone() {
		$ua = $_SERVER['HTTP_USER_AGENT'];
		return (strpos($ua,'iPhone') !== false) || (strpos($ua,'iPod') !== false) || (strpos($ua,'Android') !== false);
	}

	private function getMasterData() {
		$masterData = [];
		$masterData['user'] = $this->getLoginUser();
		return $masterData;
	}

	private function getLoginUser() {
		if (Auth::check()) {
			return \Auth::user();
		}
		return [];
	}

}