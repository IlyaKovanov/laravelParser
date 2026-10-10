<?php

namespace App\Actions\Links;

use App\Models\Project;

class AddLinksToProjectAction
{
    public function execute(Project $project, array $links): void
    {
        $project->links()->createMany($links);
    }
}
