<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Services\RagService;

class LostItemController extends Controller
{
    private $uploadDir = 'uploads';
    private $jsonFile = 'uploads.json';

    private function loadData()
    {
        if (!File::exists($this->jsonFile)) {
            return [];
        }

        $json = File::get($this->jsonFile);
        return json_decode($json, true) ?? [];
    }

    public function lostItems()
    {
        $data = $this->loadData();
        return view('lostItems', ['items' => $data]);
    }

    public function ragSearch(Request $request, RagService $ragService)
    {
        $query = $request->input('query');

        if (!$query) {
            return redirect()->route('lostItems');
        }

        $items = $this->loadData();
        $dataUpdated = false;

        // 1. INDEXING (Check for missing embeddings)
        foreach ($items as $key => &$item) {
            // Check if embedding is missing OR if it's the wrong size (Gemini = 768)
            $needsEmbedding = !isset($item['embedding']) || count($item['embedding']) !== 768;

            if ($needsEmbedding) {
                $textToEmbed = "Description: " . ($item['Description'] ?? '') .
                               " Found Date: " . ($item['DateTime'] ?? '');

                $embedding = $ragService->getEmbedding($textToEmbed);

                if (!empty($embedding)) {
                    $item['embedding'] = $embedding;
                    $dataUpdated = true;
                }
            }
        }
        unset($item);

        if ($dataUpdated) {
            File::put($this->jsonFile, json_encode($items, JSON_PRETTY_PRINT));
        }

        // 2. SEARCHING
        $queryEmbedding = $ragService->getEmbedding($query);

        // 3. RANKING & LIMITING
        $results = collect($items)->map(function ($item) use ($queryEmbedding, $ragService) {
            $score = 0;
            if (isset($item['embedding']) && !empty($queryEmbedding)) {
                $score = $ragService->cosineSimilarity($queryEmbedding, $item['embedding']);
            }
            $item['similarity_score'] = $score;
            return $item;
        })
        ->filter(function ($item) {
            return $item['similarity_score'] > 0.35; // Filter low relevance
        })
        ->sortByDesc('similarity_score')
        ->take(6); // <--- LIMITS RESULTS TO TOP 6

        return view('lostItems', ['items' => $results]);
    }

    // ... keep your existing itemDetail and showItemMap methods ...
    public function itemDetail($id)
    {
        $data = $this->loadData();
        $item = $data[$id] ?? null;
        if (!$item) abort(404, 'Item not found');
        return view('itemDetail', compact('item', 'id'));
    }

    public function showItemMap($id)
    {
        $data = $this->loadData();
        $item = $data[$id] ?? null;
        if (!$item) abort(404, 'Item not found');
        if (empty($item['Latitude']) || empty($item['Longitude'])) {
            return redirect()->route('itemDetail', $id)->with('error', 'No location data.');
        }
        return view('itemMap', compact('item', 'id'));
    }
}
