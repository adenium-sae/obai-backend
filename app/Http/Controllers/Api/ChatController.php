<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(
        private readonly ChatService $chatService
    ) {}

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => 'required|string'
        ]);
        $message = $this->chatService->ask($conversation, $request->message);
        return response()->json([
            'status' => 'success',
            'message' => $message->content,
            'role' => $message->role
        ]);
    }
}
