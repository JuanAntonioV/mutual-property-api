<?php

namespace App\Http\Controllers\Admin\Projects;

use App\Http\Controllers\Controller;
use App\Services\Admin\Projects\AdminProjectServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminProjectController extends Controller
{
    protected AdminProjectServiceInterface $adminProjectService;

    public function __construct(AdminProjectServiceInterface $adminProjectService)
    {
        $this->adminProjectService = $adminProjectService;
    }

    public function getAllProjects(): JsonResponse
    {
        $data = $this->adminProjectService->getAllProjects();
        return response()->json($data, $data['code']);
    }

    public function getProjectDetails(int $id): JsonResponse
    {
        $data = $this->adminProjectService->getProjectDetails($id);
        return response()->json($data, $data['code']);
    }

    public function createNewProject(Request $request): JsonResponse
    {
        $data = $this->adminProjectService->createNewProject($request);
        return response()->json($data, $data['code']);
    }

    public function deleteProject(int $id): JsonResponse
    {
        $data = $this->adminProjectService->deleteProject($id);
        return response()->json($data, $data['code']);
    }

    public function updateProject(int $id, Request $request): JsonResponse
    {
        $data = $this->adminProjectService->updateProject($id, $request);
        return response()->json($data, $data['code']);
    }
}
