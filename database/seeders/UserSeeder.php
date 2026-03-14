<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        /*
        | Основной пользователь системы
        */
        User::updateOrCreate(

            [
                'email' => 'a@bk.ru'
            ],

            [
                'name' => 'Alimzhan Kassenov',
                'organization_id' => 1,
                'password' => Hash::make('password')
            ]

        );


        /*
        | HR пользователь
        */
        User::updateOrCreate(

            [
                'email' => 'hr@serviceflow.test'
            ],

            [
                'name' => 'HR Manager',
                'organization_id' => 1,
                'password' => Hash::make('password')
            ]

        );


        /*
        | IT пользователь
        */
        User::updateOrCreate(

            [
                'email' => 'it@serviceflow.test'
            ],

            [
                'name' => 'IT Support',
                'organization_id' => 1,
                'password' => Hash::make('password')
            ]

        );

    }
}
