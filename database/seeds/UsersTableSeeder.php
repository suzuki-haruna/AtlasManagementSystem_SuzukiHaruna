<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            ['over_name' => 'Atlas',
            'under_name' => '一郎',
            'over_name_kana' => 'アトラス',
            'under_name_kana' => 'イチロウ',
            'mail_address' => '1@jp',
            'sex' => '3',
            'birth_day' => '2015-10-01',
            'role' => '4',
            'password' => '00000000'],
            ['over_name' => 'Atlas',
            'under_name' => '二郎',
            'over_name_kana' => 'アトラス',
            'under_name_kana' => '二ロウ',
            'mail_address' => '2@jp',
            'sex' => '3',
            'birth_day' => '2015-10-01',
            'role' => '4',
            'password' => '00000000'],
            ['over_name' => 'Atlas',
            'under_name' => '三郎',
            'over_name_kana' => 'アトラス',
            'under_name_kana' => 'サンロウ',
            'mail_address' => '3@jp',
            'sex' => '3',
            'birth_day' => '2015-10-01',
            'role' => '4',
            'password' => '00000000'],
            ['over_name' => 'Atlas',
            'under_name' => '四郎',
            'over_name_kana' => 'アトラス',
            'under_name_kana' => 'ヨンロウ',
            'mail_address' => '4@jp',
            'sex' => '3',
            'birth_day' => '2015-10-01',
            'role' => '4',
            'password' => '00000000'],
            ['over_name' => 'Atlas',
            'under_name' => '五郎',
            'over_name_kana' => 'アトラス',
            'under_name_kana' => 'ゴロウ',
            'mail_address' => '5@jp',
            'sex' => '3',
            'birth_day' => '2015-10-01',
            'role' => '4',
            'password' => '00000000']
        ]);
    }
}
