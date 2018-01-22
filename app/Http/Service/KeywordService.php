<?php

/**
 * Created by PhpStorm.
 * User: t-mizuma
 * Date: 2017/12/04
 * Time: 20:43
 */

namespace App\Http\Service;
use App\Http\Dao\KeywordsDao;

class KeywordService {
	
	/** @var KeywordsDao */
	private $keywordsDao;
	
	public function __construct( KeywordsDao $keywordsDao ) {
		$this->keywordsDao = $keywordsDao;
	}
	
	public function getKeywordsById($id) {
		return $this->keywordsDao->findItemsById($id, 'phone_number_id');
	}

	public function updateKeywordById($id, $keywords) {
		$this->keywordsDao->deleteAndUpdateById($id, $keywords, 'phone_number_id');
	}

	public function deleteByPhoneNumberId($id) {
		$this->keywordsDao->deleteById($id, 'phone_number_id');
	}

}