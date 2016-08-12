<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\ProjectTask;
use League\Fractal\TransformerAbstract;

class ProjectTaskTransformer extends TransformerAbstract
{
    public function transform(ProjectTask $projectTask)
    {
        return [
            'id' => $projectTask->id,
            'project_id' => $projectTask->project_id,
            'name' => $projectTask->name,
            'status' => $projectTask->status,
            'start_date' => $projectTask->start_date,
            'due_date' => $projectTask->due_date,
        ];
    }
}