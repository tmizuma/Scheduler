<?php

namespace Tests\Unit;

use App\Keyword;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\PhoneNumbers;

class PhotoNumberTest extends TestCase {

    const NUM_OF_PHONE   = 3;
    const NUM_OF_KEYWORDS = 3;

    public function setUp(){
        parent::setUp();
        Artisan::call('migrate:refresh');
        $this->createPhoneNumbersAndKeywords(self::NUM_OF_PHONE, self::NUM_OF_KEYWORDS);
    }

    /**
     * [テスト] 全ての電話番号情報を取得できること
     */
    public function testFindAll() {
        $response = $this->call('GET', 'api/phone/');
        $this->assertEquals($response->getStatusCode(), $this->getStatusCodeOK());
        $response->assertStatus($this->getStatusCodeOK());
        $this->assertEquals(count($response->getData()->data), 3);
    }

    /**
     * [テスト] 電話番号情報を登録できること
     */
    public function testCreatePhone() {
        $count = PhoneNumbers::count();
        $response = $this->call('POST', 'api/phone/', [
            'phone_number'          => '080-4249-8492',
            'description'           => 'description',
            'department'            => 'department',
            'person_in_charge'      => 'person_in_charge',
        ]);
        $response->assertStatus($this->getStatusCodeOK());
        $this->assertEquals($count+1, PhoneNumbers::count());

        // 必須項目は電話番号のみ
        $count = PhoneNumbers::count();
        $response = $this->call('POST', 'api/phone/', [
            'phone_number'          => '080-4249-8492'
        ]);
        $response->assertStatus($this->getStatusCodeOK());
        $this->assertEquals($count+1, PhoneNumbers::count());
    }

    /**
     * [テスト] 電話番号情報が編集できること
     */
    public function testUpdatePhone() {
        $phone = PhoneNumbers::first();
        $response = $this->call('PUT', 'api/phone/' . $phone->id, [
            'phone_number'          => '090-AAAA-AAAA',
            'description'           => 'updated_description',
            'department'            => 'updated_department',
            'person_in_charge'      => 'department_person_in_charge',
        ]);
        $response->assertStatus($this->getStatusCodeOK());
        $updated_phone = PhoneNumbers::find($phone->id);
        $this->assertEquals($updated_phone->phone_number, '090-AAAA-AAAA');
        $this->assertEquals($updated_phone->description, 'updated_description');
        $this->assertEquals($updated_phone->department, 'updated_department');
        $this->assertEquals($updated_phone->person_in_charge, 'department_person_in_charge');
    }

    /**
     * [テスト] 電話番号情報を削除できること。合わせて、キーワードも削除できていること。
     */
    public function testDeletePhone() {
        $count = PhoneNumbers::where(['deleted_at' => null])->count();
        $phone = PhoneNumbers::first();
        $this->assertNotEquals(0, Keyword::where(['phone_number_id' => $phone->id])->count());
        $response = $this->call('DELETE', 'api/phone/' . $phone->id);
        $response->assertStatus($this->getStatusCodeOK());
        $this->assertEquals($count-1, PhoneNumbers::where(['deleted_at' => null])->count());
        $this->assertEquals(0, Keyword::where(['phone_number_id' => $phone->id])->count());
    }

    /**
     * [テスト] 特定の電話番号情報を取得できること
     */
    public function testFindPhoneById() {
        $phone = PhoneNumbers::first();
        $response = $this->call('GET', 'api/phone/' . $phone->id);
        $response->assertStatus($this->getStatusCodeOK());
        $this->assertEquals(count($response->getData()), 1);
        $this->assertNotEmpty('id', $response->getData()->data->id);
    }
}
