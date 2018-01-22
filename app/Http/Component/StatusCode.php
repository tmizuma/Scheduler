<?php

/**
 * Created by PhpStorm.
 * User: t-mizuma
 * Date: 2016/12/31
 * Time: 11:21
 */

namespace App\Http\Component;

class StatusCode {
	/**
	 *
	 * jsonレスポンスのステータスコード
	 */

	const SUCCESS 						= 200;	// 正常終了
	const DM_AUTH_ERROR					= 220;	// 参照できないDMを参照しようとした
	const INVALID_REQUEST				= 400;  // Bad request
	const INVALID_ARGUMENT				= 401;	// 引数エラー
	const INVALID_OPERATION				= 405;	// 許可されていない操作を実行した
	const SERVER_ERROR					= 500;	// サーバーエラー
	const UNEXPECTED_ERROR				= 999;	// 予期せぬエラー

}