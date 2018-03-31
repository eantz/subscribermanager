<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        return view('subscribers.index');
    }
}
