<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AutomationRule;
use App\Models\AutomationAction;
use App\Models\Pipeline;

class AutomationSeeder extends Seeder
{
    public function run(): void
    {
        $pipeline = Pipeline::first();

        if (!$pipeline) {
            return;
        }

        // правило
        $rule = AutomationRule::create([
            'name' => 'Автоназначение на согласование',
            'pipeline_id' => $pipeline->id,
            'event' => 'stage_changed',
            'stage_id' => 3,
            'active' => true
        ]);

        // действие
        AutomationAction::create([
            'rule_id' => $rule->id,
            'type' => 'assign_user',
            'config' => [
                'user_id' => 1
            ],
            'position' => 1
        ]);
    }
}
