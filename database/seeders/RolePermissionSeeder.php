<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {

        $admin = Role::where('code', 'admin')->first();

        $manager = Role::where('code', 'manager')->first();

        $user = Role::where('code', 'user')->first();


        /*
         * Администратор
         */

        $admin->permissions()->sync(
            Permission::pluck('id')
        );


        /*
         * Менеджер
         */

        $manager->permissions()->sync(

            Permission::whereIn('code', [

                'request.create',
                'request.view',
                'request.move',
                'request.comment',
                'request.assign',
                'request.close'

            ])->pluck('id')

        );


        /*
         * Сотрудник
         */

        $user->permissions()->sync(

            Permission::whereIn('code', [

                'request.create',
                'request.view',
                'request.comment'

            ])->pluck('id')

        );

    }
}
