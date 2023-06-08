<?php

use Illuminate\Database\Seeder;
use App\Models\Interesting;

class InterestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Interesting::create(['name' => '⚽ Football']);
        Interesting::create(['name' => '🏀 Basketball']);
        Interesting::create(['name' => '🧘 Yoga']);
        Interesting::create(['name' => '🏊 Swimming']);
        Interesting::create(['name' => '🎾 Tennis']);
        Interesting::create(['name' => '🥋 Karate']);
        Interesting::create(['name' => '🏓 Table tennis']);
        Interesting::create(['name' => '❤ Boxing']);
    }
}
