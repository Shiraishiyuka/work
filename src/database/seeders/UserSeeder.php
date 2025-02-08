<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // ← これを追加！
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 🚨 外部キー制約を一時的に無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate(); // 🔥 全データ削除
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // ✅ 外部キー制約を再有効化

        // ユーザーデータを作成
        $users = [
            [
                'name' => '西怜奈',
                'email' => 'reina.n@coachetech.com',
                'password' => Hash::make('password1'),
            ],
            [
                'name' => '山田太郎',
                'email' => 'taro.y@coachtech.com',
                'password' => Hash::make('password2'),
            ],
            [
                'name' => '増田一世',
                'email' => 'issei.m@coachetech.com',
                'password' => Hash::make('password3'),
            ],
            [
                'name' => '山本敬吉',
                'email' => 'keikichi.y@coachetech.com',
                'password' => Hash::make('password4'),
            ],
            [
                'name' => '秋田朋美',
                'email' => 'tomomi.a@coachetech.com',
                'password' => Hash::make('password5'),
            ],
            [
                'name' => '中西教夫',
                'email' => 'norio.n@coachetech.com',
                'password' => Hash::make('password6'),
            ],
        ];

        // データを挿入
        User::insert($users);
    }
    
}
