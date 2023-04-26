<?php

use App\User;
use Illuminate\Database\Seeder;

class AdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=>'ahmed',
            'email'=>'am9514994@gmail.com',
            'phone'=>'01208982815',
            'password'=>bcrypt('123456789'),
            'type'=>'1',
        ]);
    }
}
