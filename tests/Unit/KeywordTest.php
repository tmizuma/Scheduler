<?php

namespace Tests\Unit;

use App\Keyword;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\PhoneNumbers;

class KeywordTest extends TestCase {

    const NUM_OF_PHONE   = 3;
    const NUM_OF_KEYWORDS = 3;

    public function setUp(){
        parent::setUp();
        Artisan::call('migrate:refresh');
        $this->createPhoneNumbersAndKeywords(self::NUM_OF_PHONE, self::NUM_OF_KEYWORDS);
    }

    /**
     * [テスト] キーワード一覧が取得できること
     */
    public function testFindById() {
        $phone = PhoneNumbers::first();
        $response = $this->call('GET', 'api/keyword/' . $phone->id,['keywords' => 'test1,test2,test3']);
        $response->assertStatus($this->getStatusCodeOK());
        $this->assertEquals(count($response->getData()->data), self::NUM_OF_KEYWORDS);
    }

    /**
     * [テスト] キーワード一覧を更新できること
     */
    public function testUpdate() {
        $phone = PhoneNumbers::first();
        $response = $this->call('PUT', 'api/keyword/' . $phone->id,['keywords' => 'test1,test2,test3,test4,test5']);
        $response->assertStatus($this->getStatusCodeOK());
        $this->assertEquals(Keyword::where('phone_number_id', $phone->id)->count(),5);
    }
}
