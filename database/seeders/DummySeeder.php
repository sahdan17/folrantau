<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userData = [
            // [
            //     'email'=>'prtmn@gmail.com',
            //     'password'=>bcrypt('123456'),
            //     'role'=>'admin'
            // ],
            [
                'email'=>'dwi@gmail.com',
                'password'=>bcrypt('123456'),
            ]
        ];

        foreach($userData as $key => $val){
            User::create($val);
        }
    }
}
