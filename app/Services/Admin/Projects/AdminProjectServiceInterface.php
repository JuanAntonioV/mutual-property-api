<?php

namespace App\Services\Admin\Projects;

use Illuminate\Http\Request;

interface AdminProjectServiceInterface
{
    public function getAllProjects(): array;

    public function getProjectDetails(int $id): array;

    public function deleteProject(int $id): array;

    public function createNewProject(Request $request): array;

    public function updateProject(int $id, Request $request): array;
}
