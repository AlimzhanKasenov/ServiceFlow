<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pipeline;
use App\Models\Stage;

class PipelineSeeder extends Seeder
{
    public function run(): void
    {

        $pipeline = Pipeline::create([
            'organization_id' => 1,
            'request_type_id' => 1,
            'name' => 'Основная воронка'
        ]);

        Stage::create([
            'pipeline_id' => $pipeline->id,
            'name' => 'Новая',
            'position' => 1,
            'type' => 'start'
        ]);

        Stage::create([
            'pipeline_id' => $pipeline->id,
            'name' => 'В работе',
            'position' => 2,
            'type' => 'process'
        ]);

        Stage::create([
            'pipeline_id' => $pipeline->id,
            'name' => 'Согласование',
            'position' => 3,
            'type' => 'approval'
        ]);

        Stage::create([
            'pipeline_id' => $pipeline->id,
            'name' => 'Завершено',
            'position' => 4,
            'type' => 'end'
        ]);

    }
}
