<?php

namespace App\Services\Contacts;

use App\Helpers\ResponseHelper;
use App\Repository\Contacts\ContactRepoInterface;

class ContactService implements ContactServiceInterface
{
    protected ContactRepoInterface $contactRepo;

    public function __construct(ContactRepoInterface $contactRepo)
    {
        $this->contactRepo = $contactRepo;
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
}
