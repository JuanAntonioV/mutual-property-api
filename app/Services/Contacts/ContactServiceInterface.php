<?php

namespace App\Services\Contacts;

use Illuminate\Http\Request;

interface ContactServiceInterface
{
    public function getAllContacts(): array;

    public function sendNewContact(Request $request): array;
}
