<!DOCTYPE html>
<html lang="en">
@include('layouts.header')
<body>
    @vite(['resources/css/report.css'])
    @include('layouts.bar')

    <main class="main-content">

        <div class="container" @if(!empty($imageUrl) || !empty($description)) style="display: flex; flex-direction: column; min-height: calc(90vh - 100px);" @endif>

            @if(!empty($imageUrl) || !empty($description))
                {{-- RESULT CARD --}}
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"> Upload Successful</h2>
                        <p class="card-description">Here is the result of your upload.</p>
                    </div>

                    <div class="card-content space-y-6">
                        @if(!empty($imageUrl))
                            <div>
                                <div class="image-preview-wrapper" style="display: block; margin-bottom: 0; border: 1px solid rgb(172, 172, 172);">
                                    <img src="{{ $imageUrl }}" alt="Uploaded Image" class="image-preview">
                                 </div>
                            </div>
                        @endif
                        @if(!empty($description))
                            <div>
                                <h3 class="form-label"><i class="fa-solid fa-wand-magic-sparkles"></i> AI-Generated Description</h3>
                                <div class="ai-description-box" style="display: block;">
                                    <p>{{ $description }}</p>
                                </div>
                            </div>
                        @endif
                        {{-- Show captured location --}}
                        @if(!empty($foundLocation))
                            <div>
                                <h3 class="form-label"><i class="fa-solid fa-location-dot"></i> Location Captured</h3>
                                <div class="ai-description-box" style="display: block; font-size: 0.9rem; background-color: #f4f4f4;">
                                    <p><strong>Address:</strong> {{ $foundLocation }}</p>
                                </div>
                            </div>
                        @elseif(!empty($latitude) && !empty($longitude))
                             <div>
                                <h3 class="form-label"><i class="fa-solid fa-location-dot"></i> Location Captured</h3>
                                <div class="ai-description-box" style="display: block; font-size: 0.9rem; background-color: #f4f4f4;">
                                    <p>Coordinates: {{ $latitude }}, {{ $longitude }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div style="margin-top: auto; padding-top: 1.5rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <a href="{{ route('found') }}" class="btn btn-primary">
                        <i class="fa-solid fa-plus icon"></i>
                        Report Another Found Item
                    </a>
                </div>

            @else
                {{-- UPLOAD FORM --}}
                <div class="page-header">
                    <h1> Report a Found Item</h1>
                    <p>Upload a photo to get an AI-generated description.</p>
                </div>

                <form id="found-form" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- HIDDEN LOCATION FIELDS --}}
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title"><i class="fa-solid fa-camera-retro"></i> Upload Item Photo</h2>
                            <p class="card-description">
                                The system will automatically analyze and describe it using AI recognition.
                            </p>
                        </div>
                        <div class="card-content">
                            <div class="upload-zone">
                                <i class="fa-solid fa-upload upload-icon"></i>
                                <br>
                                <label for="image-upload" class="upload-label">Click to upload image</label>
                                <p class="card-description" style="margin-top: 0.5rem;">PNG, JPG, or WEBP</p>
                                <input id="image-upload" name="file" type="file" accept="image/*" class="upload-input" required>
                            </div>
                            {{-- Location status message --}}
                            <div id="location-status" style="text-align: center; margin-top: 1rem; font-style: italic; color: #555;"></div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full" style="padding-top: 1rem; padding-bottom: 1rem; font-size: 1.125rem; margin: 10px 0px;">
                        <i class="fa-solid fa-cloud-upload"></i> Upload and Analyze Item
                    </button>
                </form>
            @endif

        </div>
    </main>

    <footer>
        &copy; {{ now()->year }} Help-Me-Find | Designed with ❤ by Bethelhem
    </footer>

    {{-- JavaScript for file input and GEOLOCATION --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('image-upload');
            const locationStatus = document.getElementById('location-status');
            const latInput = document.getElementById('latitude');
            const lonInput = document.getElementById('longitude');

            if (fileInput) {
                const uploadIcon = document.querySelector('.upload-icon');
                const uploadLabel = document.querySelector('.upload-label');
                const uploadDescription = document.querySelector('.upload-zone .card-description');

                fileInput.addEventListener('change', function(e) {
                    if (e.target.files && e.target.files.length > 0) {
                        const fileName = e.target.files[0].name;

                        // Update file upload UI
                        if (uploadIcon) {
                            uploadIcon.classList.remove('fa-upload');
                            uploadIcon.classList.add('fa-check-circle');
                            uploadIcon.style.color = 'var(--primary)';
                        }
                        if (uploadLabel) {
                            uploadLabel.textContent = 'File Selected:';
                        }
                        if (uploadDescription) {
                            uploadDescription.textContent = fileName;
                            uploadDescription.style.fontWeight = '500';
                        }

                        // ** GET LOCATION (Updated to match Report page logic) **
                        if (navigator.geolocation) {
                            if (locationStatus) locationStatus.textContent = 'Acquiring location...';
                            navigator.geolocation.getCurrentPosition(
                                function(position) {
                                    // Success
                                    if (latInput) latInput.value = position.coords.latitude;
                                    if (lonInput) lonInput.value = position.coords.longitude;
                                    if (locationStatus) {
                                        locationStatus.textContent = 'Location captured successfully!';
                                        locationStatus.style.color = 'var(--primary)';
                                    }
                                },
                                function(error) {
                                    // Error
                                    console.error("Error getting location: " + error.message);
                                    if (locationStatus) {
                                        locationStatus.textContent = 'Could not get location. Please allow location access.';
                                        locationStatus.style.color = '#ef4444'; // Red
                                    }
                                }
                            );
                        } else {
                            // Geolocation not supported
                            if (locationStatus) {
                                locationStatus.textContent = 'Geolocation is not supported by your browser.';
                                locationStatus.style.color = '#ef4444';
                            }
                        }
                    }
                });
            }
        });
    </script>

</body>
</html>
