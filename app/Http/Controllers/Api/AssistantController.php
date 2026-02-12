<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assistant;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function index()
    {
        return Assistant::all();
    }
}
