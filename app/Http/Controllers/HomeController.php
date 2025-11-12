<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\FoundItem; // <== NEW: Import the new model

class HomeController extends Controller
{
    // The method that retrieves and displays members
    public function viewPage() {
        $owner = auth()->user();
        $link = route('finder.report', $owner->qr_code_token);
        $qrCode = QrCode::size(200)->generate($link);

        return view('home', compact('qrCode', 'link'));
    }

    // --------------------- //

    private $uploadDir = 'uploads';
    private $jsonFile = 'uploads.json';

    public function home()
    {
        return view('home');
    }

    // ------------------ FOUND PAGE ------------------ //
    public function found(Request $request)
    {
        $imageUrl = '';
        $description = '';
        $now = Carbon::now();

        if ($request->isMethod('post')) {

            if (!$request->hasFile('file')) {
                return view('found', ['error' => 'No file selected']);
            }

            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension() ?: 'jpg';

            $newFilename = $this->getNextFilename($ext);
            $file->move(public_path($this->uploadDir), $newFilename);
            $filepath = public_path("{$this->uploadDir}/{$newFilename}");

            // Generate AI description
            $description = $this->generateDescriptionWithAI($filepath);

            // Get the currently authenticated user (the finder)
            $finder = auth()->user();

            // Prepare data array for both storage types
            $dataToSave = [
                'image_name' => $newFilename,
                'description' => $description,
                'finder_first_name' => $finder->firstName,
                'finder_last_name' => $finder->lastName,
                'finder_email' => $finder->email,
                'found_date' => $now->toDateTimeString(),
            ];


            // === 1. Save to MySQL Database using Eloquent ===
            FoundItem::create($dataToSave);


            // === 2. Save to JSON File (keeping the original functionality) ===
            $data = $this->loadData();
            $nextId = empty($data) ? 1 : (max(array_map('intval', array_keys($data))) + 1);

            $data[$nextId] = [
                'ImageName' => $newFilename,
                'Description' => $description,
                'DateTime' => $now->toDateTimeString(),
                'Location' => '',
                'FinderFirstName' => $finder->firstName,
                'FinderLastName' => $finder->lastName,
                'FinderEmail' => $finder->email,
                'OwnerFirstName' => "",
                'OwnerLastName' => "",
                'OwnerEmail' => "",
            ];

            $this->saveData($data);

            $imageUrl = asset("uploads/{$newFilename}");
        }

        return view('found', compact('imageUrl', 'description'));
    }

    // ------------------ LOST ITEMS ------------------ //
    // public function lostItems()
    // {
    //     $data = $this->loadData();
    //     return view('lostItems', ['items' => $data]);
    // }

    // ------------------ ITEM DETAIL ------------------ //
    // public function itemDetail($id)
    // {
    //     $data = $this->loadData();
    //     $item = $data[$id] ?? null;

    //     if (!$item) {
    //         abort(404, 'Item not found');
    //     }

    //     return view('itemDetail', compact('item', 'id'));
    // }

    // ------------------ HELPER FUNCTIONS ------------------ //

    private function getNextFilename($extension = 'jpg')
    {
        $files = File::files(public_path($this->uploadDir));
        $numbers = [];

        foreach ($files as $file) {
            $name = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            if (is_numeric($name)) {
                $numbers[] = (int)$name;
            }
        }

        $nextNum = empty($numbers) ? 1 : max($numbers) + 1;
        return "{$nextNum}.{$extension}";
    }

    private function loadData()
    {
        if (!File::exists($this->jsonFile)) {
            return [];
        }

        $json = File::get($this->jsonFile);
        return json_decode($json, true) ?? [];
    }

    private function saveData($data)
    {
        File::put($this->jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    private function generateDescriptionWithAI($imagePath)
    {
        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        $imageData = base64_encode(file_get_contents($imagePath));

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => 'Describe exactly what the item in this image is in 50 words or less. Be concise and neutral.'],
                        [
                            'inline_data' => [
                                'mime_type' => 'image/jpeg',
                                'data' => $imageData
                            ]
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::post($url, $payload);
            $json = $response->json();
            return $json['candidates'][0]['content']['parts'][0]['text'] ?? 'Automatic description failed.';
        } catch (\Exception $e) {
            return 'Automatic description failed.';
        }
    }

}
