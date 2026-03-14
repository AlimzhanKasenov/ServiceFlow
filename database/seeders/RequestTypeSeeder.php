<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RequestType;

class RequestTypeSeeder extends Seeder
{
    public function run(): void
    {

        RequestType::updateOrCreate(

            [
                'organization_id' => 1,
                'name' => 'Общие заявки'
            ],

            [
                'description' => 'Основной тип заявок системы',
                'icon' => 'ticket'
            ]

        );

    }
}
