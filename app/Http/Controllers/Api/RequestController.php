<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;

class RequestController extends Controller
{
    public function index()
    {
        return ServiceRequest::all();
    }
}
