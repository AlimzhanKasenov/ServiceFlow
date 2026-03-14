<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {

        $roles = [

            [
                'name' => 'Администратор',
                'code' => 'admin'
            ],

            [
                'name' => 'Менеджер',
                'code' => 'manager'
            ],

            [
                'name' => 'Согласующий',
                'code' => 'approver'
            ],

            [
                'name' => 'Сотрудник',
                'code' => 'user'
            ]

        ];

        foreach ($roles as $role) {

            Role::updateOrCreate(
                ['code' => $role['code']],
                $role
            );

        }

    }
}
