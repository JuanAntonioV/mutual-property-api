<?php

namespace App\Services\Subscriptions;

use App\Helpers\ResponseHelper;
use App\Models\Subscriptions\Subscription;
use App\Repository\Subscriptions\SubscriptionRepoInterface;
use App\Validators\SubscriptionValidator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionService implements SubscriptionServiceInterface
{
    protected SubscriptionRepoInterface $subscriptionRepo;
    protected SubscriptionValidator $subscriptionValidator;

    public function __construct(SubscriptionRepoInterface $subscriptionRepo, SubscriptionValidator $subscriptionValidator)
    {
        $this->subscriptionRepo = $subscriptionRepo;
        $this->subscriptionValidator = $subscriptionValidator;
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

    public function createSubscription(Request $request): array
    {
        $validator = $this->subscriptionValidator->validateCreateSubscription($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $email = $request->input('email');

            $isEmailExist = Subscription::where('email', $email)->first();

            if ($isEmailExist) {
                DB::rollBack();
                return ResponseHelper::success(null, 'Email sudah terdaftar');
            }

            $subscription = Subscription::create([
                'email' => $email,
                'is_subscribed' => true,
                'subscribed_at' => Carbon::now(),
            ]);

            if (!$subscription) {
                DB::rollBack();
                return ResponseHelper::error('Subscription gagal dibuat');
            }

            DB::commit();
            return ResponseHelper::success(null, 'Subscription berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
