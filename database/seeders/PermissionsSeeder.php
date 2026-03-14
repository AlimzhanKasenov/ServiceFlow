<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {

        $permissions = [

            [
                'name' => 'Создание заявки',
                'code' => 'request.create'
            ],

            [
                'name' => 'Просмотр заявки',
                'code' => 'request.view'
            ],

            [
                'name' => 'Перемещение заявки',
                'code' => 'request.move'
            ],

            [
                'name' => 'Комментирование',
                'code' => 'request.comment'
            ],

            [
                'name' => 'Назначение исполнителя',
                'code' => 'request.assign'
            ],

            [
                'name' => 'Закрытие заявки',
                'code' => 'request.close'
            ],

            [
                'name' => 'Управление pipeline',
                'code' => 'pipeline.manage'
            ],

            [
                'name' => 'Администрирование системы',
                'code' => 'system.admin'
            ]

        ];

        foreach ($permissions as $permission) {

            Permission::updateOrCreate(
                ['code' => $permission['code']],
                $permission
            );

        }

    }
}
