<?php

namespace App\Repository\Contacts;

use App\Models\Contacts\Contact;

class ContactRepo implements ContactRepoInterface
{
    public static function getAllContacts()
    {
        return Contact::all();
    }
}
