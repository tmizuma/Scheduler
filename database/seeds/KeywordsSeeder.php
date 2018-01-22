<?php

use Illuminate\Database\Seeder;

class KeywordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('keywords')->delete();
        DB::table('keywords')->insert([
            ['phone_number_id' => 1, 'keyword' => '年末調整'],
            ['phone_number_id' => 1, 'keyword' => '雑務'],
            ['phone_number_id' => 1, 'keyword' => '申請'],
            ['phone_number_id' => 1, 'keyword' => '備品'],
            ['phone_number_id' => 2, 'keyword' => '経費'],
            ['phone_number_id' => 2, 'keyword' => '申請'],
            ['phone_number_id' => 2, 'keyword' => '給与'],
            ['phone_number_id' => 2, 'keyword' => 'ボーナス'],
        ]);
        
    }
}
