<?php

namespace App\Services\Contacts;

use App\Helpers\ResponseHelper;
use App\Models\Contacts\Contact;
use App\Repository\Contacts\ContactRepoInterface;
use App\Validators\ContactValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactService implements ContactServiceInterface
{
    protected ContactRepoInterface $contactRepo;
    protected ContactValidator $contactValidator;

    public function __construct(ContactRepoInterface $contactRepo, ContactValidator $contactValidator)
    {
        $this->contactRepo = $contactRepo;
        $this->contactValidator = $contactValidator;
    }

    public function getAllContacts(): array
    {
        try {
            $contacts = $this->contactRepo->getAllContacts();

            if ($contacts->isEmpty()) {
                return ResponseHelper::notFound('Kontak kosong');
            }

            return ResponseHelper::success($contacts);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function sendNewContact(Request $request): array
    {
        $validator = $this->contactValidator->validateSendNewContact($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $fullName = $request->input('full_name');
            $email = $request->input('email');
            $message = $request->input('message');

            // check if the email is already have 3 in a day
            $isEmailExist = Contact::where('email', $email)
                ->whereDate('created_at', DB::raw('CURDATE()'))
                ->count();

            if ($isEmailExist >= 3) {
                DB::rollBack();
                return ResponseHelper::error('Email sudah mencapai batas maksimal');
            }

            $contact = Contact::create([
                'email' => $email,
                'full_name' => $fullName,
                'message' => $message,
            ]);

            if (!$contact) {
                DB::rollBack();
                return ResponseHelper::error('Gagal mengirim pesan');
            }

            DB::commit();
            return ResponseHelper::success(null, 'Berhasil mengirim pesan');

        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
