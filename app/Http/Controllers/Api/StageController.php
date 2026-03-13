<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stage;

class StageController extends Controller
{

    public function byPipeline($pipelineId)
    {

        return Stage::where('pipeline_id', $pipelineId)
            ->orderBy('position')
            ->get();

    }

}
