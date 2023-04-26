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
        Interesting::create(['name' => '❤️sport']);
        Interesting::create(['name' => '❤️entertainment']);
        Interesting::create(['name' => '❤️movies']);
        Interesting::create(['name' => '❤️series']);
        Interesting::create(['name' => '❤️reading']);
        Interesting::create(['name' => '❤️drawing']);
        Interesting::create(['name' => '❤️music']);
    }
}
