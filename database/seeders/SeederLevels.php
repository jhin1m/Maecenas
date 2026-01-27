<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeederLevels extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['level' => 'Cấp 1', 'experience' => '0', 'image' => '/levels/1.gif'],
            ['level' => 'Cấp 2', 'experience' => '100', 'image' => '/levels/2.gif'],
            ['level' => 'Cấp 3', 'experience' => '200', 'image' => '/levels/3.gif'],
            ['level' => 'Cấp 4', 'experience' => '300', 'image' => '/levels/4.gif'],
            ['level' => 'Cấp 5', 'experience' => '400', 'image' => '/levels/5.gif'],
            ['level' => 'Cấp 6', 'experience' => '500', 'image' => '/levels/6.gif'],
            ['level' => 'Cấp 7', 'experience' => '600', 'image' => '/levels/7.gif'],
        ];

        foreach ($levels as $level) {
            DB::table('levels')->insert($level);
        }
    }
}
