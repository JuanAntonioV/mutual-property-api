<?php

namespace App\Http\Controllers\Admin\Contacts;

use App\Http\Controllers\Controller;
use App\Services\Contacts\ContactServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    protected ContactServiceInterface $contactService;

    public function __construct(ContactServiceInterface $contactService)
    {
        $this->contactService = $contactService;
    }

    public function getAllContacts(): JsonResponse
    {
        $data = $this->contactService->getAllContacts();
        return response()->json($data, $data['code']);
    }

    public function sendNewContact(Request $request): JsonResponse
    {
        $data = $this->contactService->sendNewContact($request);
        return response()->json($data, $data['code']);
    }
}
