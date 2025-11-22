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

        // 1. INDEXING
        foreach ($items as $key => &$item) {
            // We check for 'embedding_v2'. This forces the system to re-index
            // existing items so they include the new Location and Date data.
            if (!isset($item['embedding_v2'])) {

                // ** UPDATED: We now include Location and Date in the text string **
                $textToEmbed = "Item Description: " . ($item['Description'] ?? '') .
                               ". Location Found: " . ($item['Location'] ?? 'Unknown') .
                               ". Date Found: " . ($item['DateTime'] ?? '');

                // Generate the new vector
                $embedding = $ragService->getEmbedding($textToEmbed);

                if (!empty($embedding)) {
                    $item['embedding_v2'] = $embedding; // Save to new v2 key

                    // Optional: Remove the old 'embedding' key to keep file size down
                    if (isset($item['embedding'])) {
                        unset($item['embedding']);
                    }

                    $dataUpdated = true;
                }
            }
        }
        unset($item); // Break reference

        // Save changes back to uploads.json
        if ($dataUpdated) {
            File::put($this->jsonFile, json_encode($items, JSON_PRETTY_PRINT));
        }

        // 2. SEARCHING
        $queryEmbedding = $ragService->getEmbedding($query);

        // 3. RANKING
        $results = collect($items)->map(function ($item) use ($queryEmbedding, $ragService) {
            $score = 0;
            // Compare query against the new 'embedding_v2'
            if (isset($item['embedding_v2']) && !empty($queryEmbedding)) {
                $score = $ragService->cosineSimilarity($queryEmbedding, $item['embedding_v2']);
            }
            $item['similarity_score'] = $score;
            return $item;
        })
        ->filter(function ($item) {
            // Slightly lower threshold to allow for location/date fuzzy matches
            return $item['similarity_score'] > 0.30;
        })
        ->sortByDesc('similarity_score')
        ->take(6);

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
