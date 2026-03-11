<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WeightLog;
use App\Models\WeightTarget;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run():void
    {
        //userを1件作成
        $user = User::create([
            'name' => 'tony',
            'email' => 'tony@gmail.com',
            'password' => Hash::make('AmericanAmerican'),
        ]);

        //weight_targetを1件作成
        WeightTarget::create([
            'user_id' => $user->id,
            'target_weight' => 50.0,
        ]);

        //weight_logsを35件作成（ファクトリ使用）
        WeightLog::factory()->count(35)->create([
            'user_id' => $user->id,
        ]);
    }
}
