<?php

/**
 * Created by PhpStorm.
 * User: t-mizuma
 * Date: 2017/12/04
 * Time: 20:43
 */

namespace App\Http\Service;
use App\Http\Dao\KeywordsDao;
use App\PhoneNumbers;
use App\Http\Dao\PhoneNumbersDao;
use Carbon\Carbon;

class PhoneNumbersService {
	
	/** @var PhoneNumbersDao */
	private $phoneNumbersDao;
	/** @var KeywordsDao */
	private $keywordsDao;
	
	public function __construct( PhoneNumbersDao $phoneNumbersDao, KeywordsDao $keywordsDao ) {
		$this->phoneNumbersDao = $phoneNumbersDao;
		$this->keywordsDao = $keywordsDao;
	}

	public function findAllPhoneNumbers() {
		return $this->phoneNumbersDao->findAll();
	}
	
	public function findById($id) {
		return $this->phoneNumbersDao->findById($id);
	}
	
	public function createPhoneNumber( $data ) {
		$phoneNumbers = new PhoneNumbers();
		$phoneNumbers->phone_number = $data['phone_number'];
		$phoneNumbers->description = $data['description'];
		$phoneNumbers->department = $data['department'];
		$phoneNumbers->person_in_charge = $data['person_in_charge'];
		$phoneNumbers->created_at = Carbon::now();
		$phoneNumbers->save();
		return $phoneNumbers;
	}
	
	public function updatePhoneNumber( $id, $data ) {
		PhoneNumbers::where('id', $id)
			->update($data);
	}

	public function deletePhoneNumber($id) {
		$this->phoneNumbersDao->softDelete($id);
	}

	public function searchTargetPhoneNumber(array $args) {
		$phone_number_id = $this->getSearchTargetPhoneNumberId($args);
		if (empty($phone_number_id)) {
			return [];
		}
		$phone = PhoneNumbers::where(['id' => $phone_number_id])->first();
		if (empty($phone->person_in_charge)) {
			$phone->person_in_charge = "---";
		}
		if (empty($phone->description)) {
			$phone->description = "---";
		}
		return $phone;
	}

	private function getSearchTargetPhoneNumberId(array $args) {
		// 全文一致の場合は最も長い単語の電話番号情報を返却する。一意に決定しない場合は、最も完全一致の件数が一致した電話番号情報を返却する。
		$prime = $this->getFullTextMatch($args);
		if (!empty($prime)) {
			return $this->getMostLength($prime);
		}
		// 前方一致の場合は最も長い単語の電話番号情報を返却する。一意に決定しない場合は、最も前方一致の件数が一致した電話番号情報を返却する。
		$prefixMatchPhones = $this->getPrefixTextMatch($args);
		if (!empty($prefixMatchPhones)) {
			return $this->getMostLength($prefixMatchPhones);
		}
		// 後方一致の場合は最も長い単語の電話番号情報を返却する。一意に決定しない場合は、最も後方一致の件数が一致した電話番号情報を返却する。
		$prefixMatchPhones = $this->getSuffixTextMatch($args);
		if (empty($prefixMatchPhones)) {
			return [];
		}
		return $this->getMostLength($prefixMatchPhones);
	}

	private function getFullTextMatch($args) {
		return $this->keywordsDao->fullTextMatchSearchPhoneNumberByKeywords($args);
	}

	private function getPrefixTextMatch($args) {
		return $this->keywordsDao->prefixMatchSearchPhoneNumberByKeywords($args);
	}

	private function getSuffixTextMatch($args) {
		return $this->keywordsDao->suffixMatchSearchPhoneNumberByKeywords($args);
	}

	private function getMostLength($args) {
		if (empty($args)) {
			return [];
		}
		$maxLength = 0;
		$target = [];
		foreach ($args as $item) {
			if ($maxLength <= strlen($item->keyword)) {
				$maxLength = strlen($item->keyword);
				$target[] = $item;
			}
		}
		if (count($target) == 1) {
			return $target[0]->phone_number_id;
		}
		return $this->arrayGroupById($target, 'phone_number_id');
	}

	private function  arrayGroupById(array $items, $keyName) {
		$groups = array_fill_keys(array_column($items, $keyName), []);
		foreach ($items as $item) {
			$groups[$item->$keyName][] = $item;
		}
		$target = null;
		$maxGroupLength = 0;
		foreach ($groups as $group) {
			if ($maxGroupLength <= count($group)) {
				$maxGroupLength = count($group);
				$target = $group;
			}
		}
		return $target[0]->phone_number_id;
	}
}