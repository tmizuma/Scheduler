<?php

use Illuminate\Database\Seeder;

class RoomsSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('rooms')->delete();
        DB::table('rooms')->insert([
            ['id' => 1, 'name' => '会議室A'],
            ['id' => 2, 'name' => '会議室B'],
        ]);
    }
}
