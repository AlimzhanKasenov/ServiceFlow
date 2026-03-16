<?php

namespace App\Observers;

use App\Models\Stage;
use App\Models\StageTransition;

class StageObserver
{
    /**
     * После создания стадии
     */
    public function created(Stage $stage): void
    {
        $pipelineId = $stage->pipeline_id;

        $stages = Stage::where('pipeline_id', $pipelineId)
            ->orderBy('position')
            ->get();

        foreach ($stages as $prevStage) {

            if ($prevStage->id === $stage->id) {
                continue;
            }

            StageTransition::firstOrCreate([
                'pipeline_id' => $pipelineId,
                'from_stage_id' => $prevStage->id,
                'to_stage_id' => $stage->id
            ]);

            StageTransition::firstOrCreate([
                'pipeline_id' => $pipelineId,
                'from_stage_id' => $stage->id,
                'to_stage_id' => $prevStage->id
            ]);
        }
    }
}
