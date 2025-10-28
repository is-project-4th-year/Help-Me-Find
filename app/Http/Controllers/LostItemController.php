<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\File;
use Carbon\Carbon;

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

    public function ragSearch(Request $request)
    {
        $query = $request->input('query');
        $json = $this->loadData();


        // === Option 1: Local Mock RAG ===
        // Just fuzzy search (for now)
        $filtered = collect($json)->filter(function ($item) use ($query) {
            return Str::contains(strtolower($item['Description']), strtolower($query));
        });

        // === Option 2: True RAG Integration ===
        // You can connect to a Python microservice or external API here
        // Example:

        // $response = Http::post('http://127.0.0.1:5000/rag-search', [
        //     'query' => $query,
        //     'items' => $json
        // ]);

        // $filtered = $response->json(); // expecting filtered JSON


        return view('lostItems', ['items' => $filtered]);
    }


    public function itemDetail($id)
    {
        $data = $this->loadData();
        $item = $data[$id] ?? null;

        if (!$item) {
            abort(404, 'Item not found');
        }

        return view('itemDetail', compact('item', 'id'));
    }
}
