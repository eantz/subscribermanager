<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserField;

class FieldController extends Controller
{
    
    public function index()
    {
        $allowedTypes = [
            UserField::TYPE_STRING => ucwords(UserField::TYPE_STRING),
            UserField::TYPE_DATE => ucwords(UserField::TYPE_DATE),
            UserField::TYPE_NUMBER => ucwords(UserField::TYPE_NUMBER),
            UserField::TYPE_BOOLEAN => ucwords(UserField::TYPE_BOOLEAN)
        ];

        return view('fields.index', ['allowedTypes' => $allowedTypes]);
    }
}
