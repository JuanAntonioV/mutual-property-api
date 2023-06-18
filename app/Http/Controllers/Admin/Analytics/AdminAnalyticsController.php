<?php

namespace App\Http\Controllers\Admin\Analytics;

use App\Http\Controllers\Controller;
use App\Services\Admin\Analytics\AdminAnalyticsServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminAnalyticsController extends Controller
{
    protected AdminAnalyticsServiceInterface $adminAnalyticsService;

    public function __construct(AdminAnalyticsServiceInterface $adminAnalyticsService)
    {
        $this->adminAnalyticsService = $adminAnalyticsService;
    }

    public function getStats(Request $request): JsonResponse
    {
        $data = $this->adminAnalyticsService->getAllStats();
        return response()->json($data, $data['code']);
    }
}
