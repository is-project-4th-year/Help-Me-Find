<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http; // <-- Make sure Http is imported
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

        $latitude = null;
        $longitude = null;
        $now = Carbon::now();

        if ($request->isMethod('post')) {

            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension() ?: 'jpg';

            $newFilename = $this->getNextFilename($ext);
            $file->move(public_path($this->uploadDir), $newFilename);
            $filepath = public_path("{$this->uploadDir}/{$newFilename}");

            // Generate AI description
            $description = $this->generateDescriptionWithAI($filepath);

            // Get the currently authenticated user (the finder)
            $finder = auth()->user();

            // Get location data from request
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            $locationName = $this->getLocationName($latitude, $longitude);

            $finalLocation = $locationName ?? $request->input('found_location');

            // Prepare data array for both storage types
            $dataToSave = [
                'image_name' => $newFilename,
                'description' => $description,
                // 'finder_id' => $finder->id,
                'finder_first_name' => $finder->firstName,
                'finder_last_name' => $finder->lastName,
                'finder_email' => $finder->email,
                'found_date' => $now->toDateTimeString(),
                'latitude' => $latitude,
                'longitude' => $longitude,

                // *** MODIFIED ***
                'found_location' => $finalLocation // Use the new location name
            ];


            // === 1. Save to MySQL Database using Eloquent ===
            // This will save $finalLocation to the 'found_location' column
            FoundItem::create($dataToSave);


            // === 2. Save to JSON File (keeping the original functionality) ===
            $data = $this->loadData();
            $nextId = empty($data) ? 1 : (max(array_map('intval', array_keys($data))) + 1);

            $data[$nextId] = [
                'ImageName' => $newFilename,
                'Description' => $description,
                'DateTime' => $now->toDateTimeString(),

                // *** MODIFIED ***
                'Location' => $finalLocation, // Use the new location name

                // ** NEW: Add location to JSON array **
                'Latitude' => $latitude,
                'Longitude' => $longitude,
                // ---
                'FinderId' => $finder->id,
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

        // ** NEW: Pass location variables to the view **
        return view('found', compact('imageUrl', 'description', 'latitude', 'longitude'));
    }

    // ------------------ LOST ITEMS ------------------ //
    // ... (rest of your controller methods) ...

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
        // Note: Using v1beta as in your original file
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

    // *** NEW HELPER FUNCTION ***
    /**
     * Get a human-readable location name from coordinates using Nominatim (OpenStreetMap).
     *
     * @param float|null $latitude
     * @param float|null $longitude
     * @return string|null
     */
    private function getLocationName($latitude, $longitude)
    {
        // Don't run if coordinates are missing
        if (empty($latitude) || empty($longitude)) {
            return null;
        }

        // The free Nominatim API endpoint
        $url = "https://nominatim.openstreetmap.org/reverse";

        try {
            $response = Http::withHeaders([
                // Nominatim requires a descriptive User-Agent
                'User-Agent' => 'Help-Me-Find App (Contact: your-email@example.com)'
            ])->get($url, [
                'format' => 'jsonv2', // Simple JSON format
                'lat' => $latitude,
                'lon' => $longitude,
                'zoom' => 18, // 18 is street/building level
                'addressdetails' => 0 // We only need the 'display_name'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // 'display_name' is the full, human-readable address
                // e.g., "Block 12, Addis Ababa University, Yek'a, Addis Ababa, ..."
                return $data['display_name'] ?? null;
            }

            // API call failed or returned an error
            return null;

        } catch (\Exception $e) {
            // Log::error('Nominatim reverse geocoding failed: ' . $e->getMessage());
            return null;
        }
    }

}
