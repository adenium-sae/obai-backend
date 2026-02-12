<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        return Conversation::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            "title" => "nullable|string",
            "assistant_id" => "required",
        ]);
        $conversation = Conversation::create($request->all());
        return $conversation;
    }

    public function show(Conversation $conversation)
    {
        return $conversation;
    }
}
