<?php

namespace App\Services\Subscriptions;

use Illuminate\Http\Request;

interface SubscriptionServiceInterface
{
    public function getAllSubscriptions(): array;

    public function createSubscription(Request $request): array;
}
