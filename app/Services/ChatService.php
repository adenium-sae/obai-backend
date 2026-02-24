<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Http;

class ChatService
{
    /**
     * Ask the AI assistant a question.
     *
     * @param Conversation $conversation
     * @param string $userMessage
     * @return Message
     */
    public function ask(Conversation $conversation, string $userMessage)
    {
        $conversation->messages()->create([
            "role" => "user",
            "content" => $userMessage,
        ]);
        $assistant = $conversation->assistant;
        $history = $conversation
            ->messages()
            ->oldest()
            ->get()
            ->map(function ($message) {
                return [
                    "role" => $message->role,
                    "content" => $message->content,
                ];
            })
            ->toArray();
        // Convertir formato de mensajes a formato OpenResponses
        $inputItems = [];
        
        // Agregar mensaje del sistema
        if (!empty($conversation->assistant->system_prompt)) {
            $inputItems[] = [
                "type" => "message",
                "role" => "system",
                "content" => $conversation->assistant->system_prompt,
            ];
        }
        
        // Agregar historial de mensajes
        foreach ($history as $message) {
            $inputItems[] = [
                "type" => "message",
                "role" => $message["role"],
                "content" => $message["content"],
            ];
        }
        
        $response = Http::withHeaders([
            "Authorization" => "Bearer " . config("services.openclaw.token"),
        ])->post(config("services.openclaw.url") . "/v1/responses", [
            "model" => "google-antigravity/gemini-3-flash",
            "input" => $inputItems,
            "stream" => false,
        ]);
        if ($response->failed()) {
            throw new \Exception(
                "Error while connecting to OpenClaw Gateway: " .
                    $response->body(),
            );
        }
        
        // OpenResponses devuelve la respuesta en output[0].content[0].text
        $responseData = $response->json();
        $outputItems = $responseData["output"] ?? [];
        $aiResponse = "";
        
        // Extraer el texto de la respuesta
        foreach ($outputItems as $outputItem) {
            if (isset($outputItem["type"]) && $outputItem["type"] === "message") {
                $content = $outputItem["content"] ?? [];
                foreach ($content as $contentPart) {
                    if (isset($contentPart["type"]) && $contentPart["type"] === "output_text") {
                        $aiResponse .= $contentPart["text"] ?? "";
                    }
                }
            }
        }
        
        // Fallback: si no se encontró texto, intentar output_text directamente
        if (empty($aiResponse)) {
            $aiResponse = $response->json("output.0.content.0.text", "");
        }
        
        if (empty($aiResponse)) {
            throw new \Exception(
                "Empty response from OpenClaw Gateway: " . $response->body(),
            );
        }
        $message = $conversation->messages()->create([
            "role" => "assistant",
            "content" => $aiResponse,
        ]);
        broadcast(new MessageSent($message))->toOthers();
        return $message;
    }
}
