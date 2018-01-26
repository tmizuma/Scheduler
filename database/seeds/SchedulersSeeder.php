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

        ]);
    }
}
