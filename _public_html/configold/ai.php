<?php

return [
    'provider' => env('AI_PROVIDER', 'ollama'),

    'ollama_url' => env('OLLAMA_URL', 'http://127.0.0.1:11434'),
    'ollama_model' => env('OLLAMA_MODEL', 'mistral'),

    'groq_api_key' => env('GROQ_API_KEY'),
    'groq_model' => env('GROQ_MODEL', 'llama-3.1-8b-instant'),

    'huggingface_api_key' => env('HUGGINGFACE_API_KEY'),
    'huggingface_model' => env('HUGGINGFACE_MODEL', 'mistralai/Mistral-7B-Instruct-v0.1'),

    'cache_enabled' => env('AI_CACHE_ENABLED', true),
    'cache_ttl' => env('AI_CACHE_TTL', 3600),
];


