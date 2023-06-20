<?php

namespace App\Services\Admin\Projects;

use App\Entities\FolderEntities;
use App\Helpers\FileHelper;
use App\Helpers\ResponseHelper;
use App\Models\Projects\Project;
use App\Services\Auth\AuthService;
use App\Validators\ProjectValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminProjectService implements AdminProjectServiceInterface
{
    protected ProjectValidator $projectValidator;

    public function __construct(ProjectValidator $projectValidator)
    {
        $this->projectValidator = $projectValidator;
    }

    public function getAllProjects(): array
    {
        try {
            $projects = Project::all();

            if ($projects->isEmpty()) {
                return ResponseHelper::notFound('Tidak ada data project');
            }

            return ResponseHelper::success($projects, 'Berhasil mengambil data project');
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function getProjectDetails(int $id): array
    {
        try {
            $project = Project::find($id);

            if (!$project) {
                return ResponseHelper::notFound('Project tidak ditemukan');
            }

            $project->load('detail');

            $project->logo = FileHelper::getFileUrl($project->logo);
            $project->detail->brochure_file = FileHelper::getFileUrl($project->detail->brochure_file);
            $project->detail->side_plan_image = FileHelper::getFileUrl($project->detail->side_plan_image);
            $project->detail->price_list_image = FileHelper::getFileUrl($project->detail->price_list_image);

            return ResponseHelper::success($project, 'Berhasil mengambil detail project');

        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function createNewProject(Request $request): array
    {
        $validator = $this->projectValidator->validateCreateSubscription($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $name = $request->input('name');
            $phoneNumber = $request->input('phone_number');
            $whatsappNumber = $request->input('whatsapp_number');
            $email = $request->input('email');
            $address = $request->input('address');
            $certificate = $request->input('certificate');
            $totalUnit = $request->input('total_unit');
            $area = $request->input('area');
            $facilities = $request->input('facilities');
            $mapUrl = $request->input('map_url');
            $description = $request->input('description');

            $logoImage = $request->file('logo_image');
            $brochureFile = $request->file('brochure_file');
            $priceListImage = $request->file('price_list_image');
            $sidePlanImage = $request->file('side_plan_image');

            if ($phoneNumber) {
                $phoneNumber = AuthService::getFormattedPhone($phoneNumber);
            }

            if ($whatsappNumber) {
                $whatsappNumber = AuthService::getFormattedPhone($whatsappNumber);
            }

            $projectData = [
                'name' => $name,
                'slug' => Str::slug($name),
                'address' => $address,
                'phone_number' => $phoneNumber,
                'whatsapp_number' => $whatsappNumber,
                'logo' => $logoImage,
                'email' => $email,
                'map_url' => $mapUrl,
                'description' => $description,
            ];

            $projectDetail = [
                'total_unit' => $totalUnit,
                'certificate' => $certificate,
                'area' => $area,
                'facilities' => $facilities,
                'brochure_file' => $brochureFile,
                'price_list_image' => $priceListImage,
                'side_plan_image' => $sidePlanImage,
            ];

            $project = Project::create($projectData);

            $project->detail()->create($projectDetail);

            $projectPath = FolderEntities::PROJECT_FOLDER . $project->id;

            if ($request->hasFile('logo_image')) {
                $logoImagePath = FileHelper::uploadFile($logoImage, $projectPath, 'logo');
            }

            if ($request->hasFile('brochure_file')) {
                $brochureFilePath = FileHelper::uploadFile($brochureFile, $projectPath, 'brochure');
            }

            if ($request->hasFile('price_list_image')) {
                $priceListImagePath = FileHelper::uploadFile($priceListImage, $projectPath, 'price_list');
            }

            if ($request->hasFile('side_plan_image')) {
                $sidePlanImagePath = FileHelper::uploadFile($sidePlanImage, $projectPath, 'side_plan');
            }

            $project->logo = $logoImagePath ?? $project->logo;
            $project->save();

            $project->detail->brochure_file = $brochureFilePath ?? $project->detail->brochure_file;
            $project->detail->price_list_image = $priceListImagePath ?? $project->detail->price_list_image;
            $project->detail->side_plan_image = $sidePlanImagePath ?? $project->detail->side_plan_image;
            $project->detail->save();

            DB::commit();
            return ResponseHelper::success(null, 'Berhasil membuat project baru');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function deleteProject(int $id): array
    {
        DB::beginTransaction();
        try {
            $project = Project::find($id);

            if (!$project) {
                return ResponseHelper::notFound('Project tidak ditemukan');
            }

            $project->delete();

            DB::commit();
            return ResponseHelper::success(null, 'Berhasil menghapus project');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function updateProject(int $id, Request $request): array
    {
        $validator = $this->projectValidator->validateUpdateProject($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $name = $request->input('name');
            $phoneNumber = $request->input('phone_number');
            $whatsappNumber = $request->input('whatsapp_number');
            $email = $request->input('email');
            $address = $request->input('address');
            $certificate = $request->input('certificate');
            $totalUnit = $request->input('total_unit');
            $area = $request->input('area');
            $facilities = $request->input('facilities');
            $mapUrl = $request->input('map_url');
            $description = $request->input('description');
            $status = $request->input('status');

            $logoImage = $request->file('logo_image');
            $brochureFile = $request->file('brochure_file');
            $priceListImage = $request->file('price_list_image');
            $sidePlanImage = $request->file('side_plan_image');

            $project = Project::find($id);

            if (!$project) {
                return ResponseHelper::notFound('Project tidak ditemukan');
            }

            if ($phoneNumber) {
                $phoneNumber = AuthService::getFormattedPhone($phoneNumber);
            }

            if ($whatsappNumber) {
                $whatsappNumber = AuthService::getFormattedPhone($whatsappNumber);
            }

            $projectPath = FolderEntities::PROJECT_FOLDER . $project->id;

            if ($request->hasFile('logo_image')) {
                $logoImagePath = FileHelper::uploadFile($logoImage, $projectPath, 'logo');
            }

            if ($request->hasFile('brochure_file')) {
                $brochureFilePath = FileHelper::uploadFile($brochureFile, $projectPath, 'brochure');
            }

            if ($request->hasFile('price_list_image')) {
                $priceListImagePath = FileHelper::uploadFile($priceListImage, $projectPath, 'price_list');
            }

            if ($request->hasFile('side_plan_image')) {
                $sidePlanImagePath = FileHelper::uploadFile($sidePlanImage, $projectPath, 'side_plan');
            }


            $projectData = [
                'name' => $name ?? $project->name,
                'slug' => Str::slug($name) ?? $project->slug,
                'address' => $address ?? $project->address,
                'phone_number' => $phoneNumber ?? $project->phone_number,
                'whatsapp_number' => $whatsappNumber ?? $project->whatsapp_number,
                'email' => $email ?? $project->email,
                'map_url' => $mapUrl ?? $project->map_url,
                'description' => $description ?? $project->description,
                'status' => $status ?? $project->status,
                'logo' => $logoImagePath ?? $project->logo,
            ];

            $projectDetail = [
                'total_unit' => $totalUnit ?? $project->detail->total_unit,
                'certificate' => $certificate ?? $project->detail->certificate,
                'area' => $area ?? $project->detail->area,
                'facilities' => $facilities ?? $project->detail->facilities,
                'brochure_file' => $brochureFilePath ?? $project->detail->brochure_file,
                'price_list_image' => $priceListImagePath ?? $project->detail->price_list_image,
                'side_plan_image' => $sidePlanImagePath ?? $project->detail->side_plan_image,
            ];

            $project->update($projectData);
            $project->detail->update($projectDetail);

            DB::commit();
            return ResponseHelper::success(null, 'Berhasil mengubah project');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
