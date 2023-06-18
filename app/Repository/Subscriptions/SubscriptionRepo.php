<?php

namespace App\Repository\Subscriptions;

use App\Models\Subscriptions\Subscription;

class SubscriptionRepo implements SubscriptionRepoInterface
{
    public static function getAllSubscriptions()
    {
        return Subscription::all();
    }
}
