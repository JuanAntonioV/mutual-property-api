<?php

namespace App\Http\Controllers\Admin\Subscriptions;

use App\Http\Controllers\Controller;
use App\Services\Subscriptions\SubscriptionServiceInterface;
use Illuminate\Http\JsonResponse;

class AdminSubcriptionController extends Controller
{
    protected SubscriptionServiceInterface $subscriptionService;

    public function __construct(SubscriptionServiceInterface $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function getAllSubscriptions(): JsonResponse
    {
        $data = $this->subscriptionService->getAllSubscriptions();
        return response()->json($data, $data['code']);
    }
}
