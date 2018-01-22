<?php

use Illuminate\Database\Seeder;

class PhoneNumbersSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('phone_numbers')->delete();
        DB::table('phone_numbers')->insert([
            ['department' => '総務部', 'phone_number' => '080-1234-5671', 'description' => '経理を担当', 'person_in_charge' => '水馬拓也', 'created_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['department' => '経理部', 'phone_number' => '080-1234-5672', 'description' => '総務を担当', 'person_in_charge' => '山田太郎', 'created_at' => DB::raw('CURRENT_TIMESTAMP')],
            ['department' => '人事部', 'phone_number' => '080-1234-5673', 'description' => '人事を担当', 'person_in_charge' => '田中花子', 'created_at' => DB::raw('CURRENT_TIMESTAMP') ],
            ['department' => '営業部', 'phone_number' => '080-1234-5674', 'description' => '営業を担当', 'person_in_charge' => '木村拓哉', 'created_at' => DB::raw('CURRENT_TIMESTAMP') ]
        ]);
    }
}
