<?php

namespace App\Actions\Projects;

use App\Actions\Links\AddLinksToProjectAction;
use App\Actions\Selectors\AddSelectorsToProjectAction;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class CreateProjectAction
{
    public function __construct(
        private AddLinksToProjectAction $addLinks,
        private AddSelectorsToProjectAction $addSelectors,
    ) {}

    public function execute(array $data, int $userId): array
    {
        return DB::transaction(function () use ($data, $userId) {
            $project = Project::firstOrCreate(
                ['base_url' => $data['base_url']],
                [
                    'user_id'     => $userId,
                    'name'        => $data['name'],
                    'base_url'    => $data['base_url'],
                    'description' => $data['description'] ?? null,
                ]
            );

            if (!$project->wasRecentlyCreated) {
                return ['project' => $project, 'created' => false];
            }

            if (!empty($data['links'])) {
                $this->addLinks->execute($project, $data['links']);
            }

            if (!empty($data['selectors'])) {
                $this->addSelectors->execute($project, $data['selectors']);
            }

            return ['project' => $project, 'created' => true];
        });
    }
}
