<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $baseUrl = 'http://127.0.0.1:11434/api/chat';
    protected $model = 'llama3.2:1b';

    public function ask(string $message, array $history = [], string $systemContext = '')
    {
        $messages = [];

        if ($systemContext) {
            $messages[] = [
                'role' => 'system',
                'content' => $systemContext,
            ];
        }

        foreach ($history as $msg) {
            $messages[] = [
                'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                'content' => $msg['contenu'],
            ];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $message,
        ];

        try {
            $response = Http::timeout(60)->post($this->baseUrl, [
                'model' => $this->model,
                'messages' => $messages,
                'stream' => false,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['message']['content'] ?? 'Désolé, je n\'ai pas pu générer de réponse.';
            }

            Log::error('Erreur Ollama API', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('Exception Ollama', ['message' => $e->getMessage()]);
        }

        return 'Erreur de connexion à l\'assistant IA. Réessayez plus tard.';
    }
}