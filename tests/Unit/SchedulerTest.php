<?php

namespace Tests\Unit;

use App\Keyword;
use App\Scheduler;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\SCHEDULENumbers;

class SchedulerTest extends TestCase {

    const NUM_OF_ROOM   = 3;

    private $rooms;

    public function setUp(){
        parent::setUp();
        Artisan::call('migrate:refresh');
        $this->rooms = $this->createRooms(self::NUM_OF_ROOM);
    }

    /**
     * [テスト] 本日日付のスケジュールを取得できること
     */
    public function testFindTodaySchedule() {
        $target_date = date('Y-m-d');
        $this->createTargetDateSchedule('1999-01-01', '10:00:00', '11:00:00'); // 対象外日付
        $this->createTargetDateSchedule($target_date, '10:00:00', '11:00:00');
        $response = $this->call('GET', 'api/scheduler/');
        $this->assertEquals($response->getStatusCode(), $this->getStatusCodeOK());
        $response->assertStatus($this->getStatusCodeOK());
        $this->assertEquals(count($response->getData()->data), 1);
    }

    /**
     * [テスト] 日付、会議室指定でスケジュールを取得できること
     */
    public function testFindTargetDateAndRoomIdSchedule() {
        $target_date = date('Y-m-d');
        $this->createTargetDateSchedule('1999-01-01', '10:00:00', '11:00:00'); // 対象外日付
        $this->createTargetDateSchedule($target_date, '10:00:00', '11:00:00');
        $response = $this->call('GET', 'api/scheduler/', [
            'target_date'      => $target_date,
            'room_id'   => $this->rooms[0]->id
        ]);
        $this->assertEquals($response->getStatusCode(), $this->getStatusCodeOK());
        $response->assertStatus($this->getStatusCodeOK());
        $this->assertEquals(count($response->getData()->data), 1);
    }

    /**
     * [テスト] スケジュールを新規登録できること
     */
    public function testCreateScheduler() {
        $count = Scheduler::count();
        $target_date = date('Y-m-d');
        $response = $this->call('POST', 'api/scheduler/', [
            'user_name'    => 'test_user',
            'start_time'   => $target_date . ' 10:00:00',
            'end_time'     => $target_date . ' 11:00:00',
            'room_id'      => $this->rooms[0]->id
        ]);
        $response->assertStatus($this->getStatusCodeOK());
        $this->assertEquals($count+1, Scheduler::count());
    }

    /**
     * [テスト] 重複するスケジュールは登録できないこと
     */
    public function testCreateDuplicateScheduler() {
        $target_date = date('Y-m-d');
        $this->createTargetDateSchedule($target_date, '10:30:00', '11:30:00');
        $count = Scheduler::count();
        $response = $this->call('POST', 'api/scheduler/', [
            'user_name'    => 'test_user',
            'description'  => 'test_description',
            'start_time'   => $target_date . ' 10:00:00',
            'end_time'     => $target_date . ' 11:00:00',
            'room_id'      => $this->rooms[0]->id
        ]);
        $response->assertStatus($this->getStatusCodeDuplicateSchedule());
        $this->assertEquals($count, Scheduler::count());
    }

    /**
     * [テスト] スケジュールが編集できること
     */
    public function testUpdateSchedule() {
        $target_date = date('Y-m-d');
        $this->createTargetDateSchedule($target_date, '12:00:00', '13:00:00');
        $scheduler = $this->createTargetDateSchedule($target_date, '10:00:00', '11:00:00');
        $response = $this->call('PUT', 'api/scheduler/' . $scheduler->id, [
            'user_name'    => 'updated_test_user',
            'description'  => 'updated_test_description',
            'start_time'   => $target_date . ' 11:00:00',
            'end_time'     => $target_date . ' 12:00:00',
            'room_id'      => $this->rooms[1]->id
        ]);
        $response->assertStatus($this->getStatusCodeOK());
        $updated_scheduler = Scheduler::find($scheduler->id);
        $this->assertEquals($updated_scheduler->user_name, 'updated_test_user');
        $this->assertEquals($updated_scheduler->description, 'updated_test_description');
        $this->assertEquals($updated_scheduler->start_time, $target_date . ' 11:00:00');
        $this->assertEquals($updated_scheduler->end_time, $target_date . ' 12:00:00');
        $this->assertEquals($updated_scheduler->room_id, $this->rooms[1]->id);
    }

    /**
     * [テスト] 重複するようなスケジュールへ編集ができないこと
     */
    public function testUpdateDuplicateSchedule() {
        $target_date = date('Y-m-d');
        $this->createTargetDateSchedule($target_date, '10:00:00', '11:00:00');
        $scheduler = $this->createTargetDateSchedule($target_date, '11:00:00', '12:00:00');
        $response = $this->call('PUT', 'api/scheduler/' . $scheduler->id, [
            'user_name'    => 'updated_test_user',
            'description'  => 'updated_test_description',
            'start_time'   => $target_date . ' 10:30:00',
            'end_time'     => $target_date . ' 11:00:00',
            'room_id'      => $this->rooms[0]->id
        ]);
        $response->assertStatus($this->getStatusCodeDuplicateSchedule());
        $updated_scheduler = Scheduler::find($scheduler->id);
        $this->assertEquals($updated_scheduler->user_name, 'test_user');
        $this->assertEquals($updated_scheduler->description, 'test_description');
        $this->assertEquals($updated_scheduler->start_time, $target_date . ' 11:00:00');
        $this->assertEquals($updated_scheduler->end_time, $target_date . ' 12:00:00');
        $this->assertEquals($updated_scheduler->room_id, $this->rooms[0]->id);
    }

    /**
     * [テスト] スケジュールを削除できること
     */
    public function testDeleteSCHEDULE() {
        $target_date = date('Y-m-d');
        $scheduler = $this->createTargetDateSchedule($target_date, '10:00:00', '11:00:00');
        $count = Scheduler::where(['deleted_at' => null])->count();
        $response = $this->call('DELETE', 'api/scheduler/' . $scheduler->id);
        $response->assertStatus($this->getStatusCodeOK());
        $this->assertEquals($count-1, Scheduler::where(['deleted_at' => null])->count());
    }

    /**
     * [テスト] 特定のスケジュールを取得できること
     */
    public function testFindSCHEDULEById() {
        $target_date = date('Y-m-d');
        $scheduler = $this->createTargetDateSchedule($target_date, '10:00:00', '11:00:00');
        $response = $this->call('GET', 'api/scheduler/' . $scheduler->id);
        $response->assertStatus($this->getStatusCodeOK());
        $this->assertEquals(count($response->getData()), 1);
        $this->assertNotEmpty('id', $response->getData()->data->id);
    }

    private function createTargetDateSchedule($target_date, $start_time, $end_time) {
        $scheduler = new Scheduler();
        $scheduler->room_id = $this->rooms[0]->id;
        $scheduler->user_name = 'test_user';
        $scheduler->description = 'test_description';
        $scheduler->start_time = $target_date . ' ' . $start_time;
        $scheduler->end_time = $target_date . ' ' . $end_time;
        $scheduler->save();
        return $scheduler;
    }
}
