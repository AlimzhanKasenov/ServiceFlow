<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {

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

    }
}
