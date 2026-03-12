<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\WeightLog;


class WeightLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = WeightLog::class;

    public function definition():array
    {
         //ランダムな運動内容の配列
        $exercises = [
            'ランニング', 'ウォーキング', 'ヨガ', '筋トレ', 
            '水泳', 'サイクリング', 'ダンス', 'ホットヨガ',
            '登山','ハイキング','卓球（硬球)','卓球（ラージボール)','子どもと公園で追いかけっこ'
        ];


        return [
            'date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'weight' => $this->faker->randomFloat(1, 60, 120), //小数点以下1桁
            'calories' => $this->faker->numberBetween(1000, 5000),
            'exercise_time' => $this->faker->time('H:i'), 
            'exercise_content' => $this->faker->randomElement($exercises),
        ];
    }
}
