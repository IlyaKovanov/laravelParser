<?php

namespace App\Actions\Selectors;

use App\Models\Project;

class AddSelectorsToProjectAction
{
    public function execute(Project $project, array $selectors): void
    {
        $project->selectors()->createMany($selectors);
    }
}
