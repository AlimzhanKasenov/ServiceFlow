<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            OrganizationSeeder::class,
            RequestTypeSeeder::class,
            PipelineSeeder::class
        ]);


        /*
        |---------------------------------------
        | Основной пользователь системы
        |---------------------------------------
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
        |---------------------------------------
        | RBAC система
        |---------------------------------------
        */

        $this->call([
            AutomationSeeder::class,
            PermissionsSeeder::class,
            RolesSeeder::class,
            RolePermissionSeeder::class

        ]);

    }
}
