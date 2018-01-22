<?php

use Illuminate\Database\Seeder;

class SchedulersSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('schedulers')->delete();
        DB::table('schedulers')->insert([
            ['id' => 1, 'room_id' => 1, 'user_name' => '山田', 'description' => '経理を担当', 'start_time' => '2018-01-22 21:00:00', 'end_time' => '2018-01-22 22:00:00'],
            ['id' => 2, 'room_id' => 1, 'user_name' => '田中','description' => '総務を担当', 'start_time' => '2018-01-22 21:00:00', 'end_time' => '2018-01-22 22:00:00'],
            ['id' => 3, 'room_id' => 2, 'user_name' => '水馬','description' => '人事を担当', 'start_time' => '2018-01-21 21:00:00', 'end_time' => '2018-01-21 22:00:00'],
            ['id' => 4, 'room_id' => 2, 'user_name' => '田村','description' => '営業を担当', 'start_time' => '2018-01-21 21:00:00', 'end_time' => '2018-01-21 22:00:00']
        ]);
    }
}
