<?php

namespace App\Services\Admin\Analytics;

use App\Helpers\ResponseHelper;
use App\Models\Contacts\Contact;
use App\Models\Products\Product;
use App\Models\Projects\Project;
use App\Models\Users\User;

class AdminAnalyticsService implements AdminAnalyticsServiceInterface
{

    public function getAllStats(): array
    {
        try {
            $totalUser = User::count();
            $totalProperty = Product::count();
            $totalDeveloper = Project::count();
            $totalContact = Contact::count();

            $data = [
                'total_user' => $totalUser,
                'total_property' => $totalProperty,
                'total_developer' => $totalDeveloper,
                'total_contact' => $totalContact,
            ];

            return ResponseHelper::success($data);

        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
