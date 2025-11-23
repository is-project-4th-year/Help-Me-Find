<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FoundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class FinderReportController extends Controller
{
    private $uploadDir = 'uploads';
    private $jsonFile = 'uploads.json';

    public function showReportForm($token){
        $owner = User::where('qr_code_token', $token)->firstOrFail();
        return view('report', compact('owner'));
    }

    public function submitReport(Request $request, $token)
    {
        // 1. Find Owner & Finder
        $owner = User::where('qr_code_token', $token)->firstOrFail();
        $finder = Auth::user();

        // 2. Validate Request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // 3. Handle File Upload
        $file = $request->file('image');
        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $newFilename = $this->getNextFilename($ext);

        $file->move(public_path($this->uploadDir), $newFilename);
        $filepath = public_path("{$this->uploadDir}/{$newFilename}");

        // 4. Generate AI Description
        $description = $this->generateDescriptionWithAI($filepath);

        // 5. Determine Location
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $locationName = $this->getLocationName($latitude, $longitude);
        $now = Carbon::now();

        // 6. Prepare Data for Database
        $dataToSave = [
            'image_name' => $newFilename,
            'description' => $description,

            // Finder details
            'finder_first_name' => $finder->firstName,
            'finder_last_name' => $finder->lastName,
            'finder_email' => $finder->email,

            // Owner details
            'owner_first_name' => $owner->firstName,
            'owner_last_name' => $owner->lastName,
            'owner_email' => $owner->email,

            'found_date' => $now->toDateTimeString(),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'found_location' => $locationName
        ];

        // 7. Save to MySQL Database
        FoundItem::create($dataToSave);

        // 8. Save to JSON (Legacy)
        $data = $this->loadData();
        $nextId = empty($data) ? 1 : (max(array_map('intval', array_keys($data))) + 1);

        $data[$nextId] = [
            'ImageName' => $newFilename,
            'Description' => $description,
            'DateTime' => $now->toDateTimeString(),
            'Location' => $locationName,
            'Latitude' => $latitude,
            'Longitude' => $longitude,

            'FinderId' => $finder->id,
            'FinderFirstName' => $finder->firstName,
            'FinderLastName' => $finder->lastName,
            'FinderEmail' => $finder->email,

            'OwnerId' => $owner->id,
            'OwnerFirstName' => $owner->firstName,
            'OwnerLastName' => $owner->lastName,
            'OwnerEmail' => $owner->email,
        ];

        $this->saveData($data);

        // 9. Redirect to Chat with Owner
        // ** UPDATED: Includes pre-filled message and image path **
        return redirect()->route('chat.with', [
            'user' => $owner->id,
            'message' => 'Hello. I found this item of yours.',
            'image' => 'uploads/' . $newFilename
        ]);
    }

    // --- Helper Functions ---

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
                        ['text' => 'Describe exactly what the item in this image is in 50 words or less. Be concise and neutral. But keep in mind the following points: 1) describe its physical traits (color, shape, material, condition). 2) Describe the brand is possible 3) Do not describe background elements. 4) Do not describe the what the screen displays. 5) Do not use introductory phrases like "The image shows". 6) Use a minimum of 80 words.'],
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
