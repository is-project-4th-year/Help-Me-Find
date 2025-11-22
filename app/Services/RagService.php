<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RagService
{
    protected $apiKey;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/text-embedding-004:embedContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    /**
     * Generate vector embedding using Google Gemini.
     */
    public function getEmbedding(string $text): array
    {
        // Gemini requires the API key in the URL query string
        $url = "{$this->baseUrl}?key={$this->apiKey}";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($url, [
            'content' => [
                'parts' => [
                    ['text' => $text]
                ]
            ]
        ]);

        if ($response->failed()) {
            // You might want to log $response->body() here for debugging
            return [];
        }

        // Gemini returns the vector in ['embedding']['values']
        return $response->json('embedding.values') ?? [];
    }

    /**
     * Calculate Cosine Similarity (Same math, different vectors)
     */
    public function cosineSimilarity(array $vecA, array $vecB): float
    {
        // Gemini vectors are size 768.
        // If vectors have different sizes (e.g. mixing OpenAI vs Gemini), result is invalid.
        if (empty($vecA) || empty($vecB) || count($vecA) !== count($vecB)) {
            return 0.0;
        }

        $dotProduct = 0;
        $magnitudeA = 0;
        $magnitudeB = 0;

        foreach ($vecA as $key => $value) {
            $dotProduct += $value * $vecB[$key];
            $magnitudeA += $value * $value;
            $magnitudeB += $vecB[$key] * $vecB[$key];
        }

        $magnitudeA = sqrt($magnitudeA);
        $magnitudeB = sqrt($magnitudeB);

        if ($magnitudeA * $magnitudeB == 0) {
            return 0.0;
        }

        return $dotProduct / ($magnitudeA * $magnitudeB);
    }
}
