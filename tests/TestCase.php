<?php

namespace Tests;

use App\Keyword;
use App\PhoneNumbers;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;


abstract class TestCase extends BaseTestCase {
    use CreatesApplication;

    const HTTP_RESPONSE_CODE_OK = 200;

    protected function createPhoneNumbers( $num ) {
        $array = [];
        for( $i=0 ; $i<$num ; $i++ ) {
            $phone = new PhoneNumbers();
            $phone->department = 'TEST_' .$i;
            $phone->phone_number = '080-1234-567' . $i;
            $phone->description = 'description_' .$i;
            $phone->person_in_charge = 'person_in_charge_' .$i;
            $phone->save();
            $array[] =$phone;
        }
        return $array;
    }

    protected function createPhoneNumbersAndKeywords($phone_num, $keyword_num) {
        $phones = $this->createPhoneNumbers($phone_num);
        foreach ($phones as $phone) {
            $phone_number_id = $phone->id;
            for($i=1;$i<=$keyword_num;$i++) {
                $keyword = new Keyword();
                $keyword->phone_number_id = $phone_number_id;
                $keyword->keyword = 'keyword_' . $i;
                $keyword->save();
            }
        }
    }

    protected function getStatusCodeOK() {
        return self::HTTP_RESPONSE_CODE_OK;
    }
}
