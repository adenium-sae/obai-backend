<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Gemini\Laravel\Facades\Gemini;

class ChatService
{
    public function ask(Conversation $conversation, string $userMessage)
    {
        $conversation->messages()->create([
            'role' => 'user',
            'content' => $userMessage
        ]);
        $assistant = $conversation->assistant;
        $fullPrompt = "INSTRUCCIONES DE PERSONALIDAD: " . $assistant->system_prompt . "\n\n" .
                    "MENSAJE DEL USUARIO: " . $userMessage;
        $response = Gemini::generativeModel('gemini-2.5-flash')->generateContent($fullPrompt);
        $aiResponse = $response->text();
        $message = $conversation->messages()->create([
            'role' => 'assistant',
            'content' => $aiResponse
        ]);
        broadcast(new MessageSent($message))->toOthers();
        return $message;
    }
}