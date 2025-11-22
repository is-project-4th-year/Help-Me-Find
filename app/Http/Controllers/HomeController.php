<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\FoundItem;

class HomeController extends Controller
{
    private $uploadDir = 'uploads';
    private $jsonFile = 'uploads.json';

    public function viewPage() {
        $owner = auth()->user();
        // Ensure the user is authenticated before accessing properties
        if ($owner) {
            $link = route('finder.report', $owner->qr_code_token);
            $qrCode = QrCode::size(200)->generate($link);
            return view('home', compact('qrCode', 'link'));
        }
        return redirect()->route('login');
    }

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
        $foundLocation = null; // Variable to hold the address name

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

            // 1. Generate AI description
            $description = $this->generateDescriptionWithAI($filepath);

            // 2. Get Location Coordinates
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            // 3. Get Human-Readable Location Name (Same logic as Report)
            $foundLocation = $this->getLocationName($latitude, $longitude);

            $finder = auth()->user();
            $now = Carbon::now();

            // 4. Prepare Data
            $dataToSave = [
                'image_name' => $newFilename,
                'description' => $description,
                'finder_first_name' => $finder->firstName,
                'finder_last_name' => $finder->lastName,
                'finder_email' => $finder->email,
                'found_date' => $now->toDateTimeString(),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'found_location' => $foundLocation // Save the name here
            ];

            // 5. Save to MySQL
            FoundItem::create($dataToSave);

            // 6. Save to JSON (Legacy support)
            $data = $this->loadData();
            $nextId = empty($data) ? 1 : (max(array_map('intval', array_keys($data))) + 1);

            $data[$nextId] = [
                'ImageName' => $newFilename,
                'Description' => $description,
                'DateTime' => $now->toDateTimeString(),
                'Location' => $foundLocation, // Save name to JSON
                'Latitude' => $latitude,
                'Longitude' => $longitude,
                'FinderId' => $finder->id,
                'FinderFirstName' => $finder->firstName,
                'FinderLastName' => $finder->lastName,
                'FinderEmail' => $finder->email,
            ];

            $this->saveData($data);

            $imageUrl = asset("uploads/{$newFilename}");
        }

        // Pass the location name to the view so the user can see it
        return view('found', compact('imageUrl', 'description', 'latitude', 'longitude', 'foundLocation'));
    }

    // ------------------ HELPER FUNCTIONS ------------------ //

    private function getNextFilename($extension = 'jpg')
    {
        if (!File::exists(public_path($this->uploadDir))) {
            File::makeDirectory(public_path($this->uploadDir), 0755, true);
        }

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

    /**
     * Get a human-readable location name from coordinates using Nominatim.
     */
    private function getLocationName($latitude, $longitude)
    {
        if (empty($latitude) || empty($longitude)) {
            return null;
        }

        $url = "https://nominatim.openstreetmap.org/reverse";

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Help-Me-Find App'
            ])->get($url, [
                'format' => 'jsonv2',
                'lat' => $latitude,
                'lon' => $longitude,
                'zoom' => 18,
                'addressdetails' => 0
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['display_name'] ?? null;
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
