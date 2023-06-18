<?php

namespace App\Services\Subscriptions;

use App\Helpers\ResponseHelper;
use App\Repository\Subscriptions\SubscriptionRepoInterface;

class SubscriptionService implements SubscriptionServiceInterface
{
    protected SubscriptionRepoInterface $subscriptionRepo;

    public function __construct(SubscriptionRepoInterface $subscriptionRepo)
    {
        $this->subscriptionRepo = $subscriptionRepo;
    }
    
    public function getAllSubscriptions(): array
    {
        try {
            $subscriptions = $this->subscriptionRepo->getAllSubscriptions();

            if ($subscriptions->isEmpty()) {
                return ResponseHelper::notFound('Subscriptions kosong');
            }

            return ResponseHelper::success($subscriptions);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
