<?php

namespace Tests;

use App\Room;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;


abstract class TestCase extends BaseTestCase {
    use CreatesApplication;

    const HTTP_RESPONSE_CODE_OK = 200;

    protected function createRooms( $num ) {
        $array = [];
        for( $i=0 ; $i<$num ; $i++ ) {
            $room = new Room();
            $room->name = 'TEST_' .$i;
            $room->save();
            $array[] =$room;
        }
        return $array;
    }

    protected function getStatusCodeOK() {
        return self::HTTP_RESPONSE_CODE_OK;
    }
}
