<!DOCTYPE html>
<html lang="en">
@include('layouts.header')
<body>
    {{-- Reuse the report CSS from found.blade.php --}}
    @vite(['resources/css/report.css'])
    @include('layouts.bar')

    <main class="main-content">
        <div class="container">

            {{-- Owner Info Card --}}
            <div class="card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <h2 class="card-title" style="color: var(--primary);"><i class="fa fa-user-circle-o fa-fw"></i> This item belongs to <b>{{ $owner->firstName }} {{ $owner->lastName }}</b></h2>
                    <p class="card-description">
                        {{-- This item belongs to <strong style="color: #333;"></strong>. --}}
                    </p>
                </div>
            </div>

            {{-- Report Form --}}
            <form id="report-form" method="POST" action="{{ route('finder.submit', $owner->qr_code_token) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Hidden Location Fields --}}
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fa-solid fa-camera-retro"></i> Upload Item Photo</h2>
                        <p class="card-description">
                            Upload a photo to analyze and notify the owner. The description will be generated automatically by AI.
                        </p>
                    </div>

                    <div class="card-content">
                        {{-- Image Upload Zone --}}
                        <div class="upload-zone">
                            <i class="fa-solid fa-upload upload-icon"></i>
                            <br>
                            <label for="image-upload" class="upload-label">Click to upload image</label>
                            <p class="card-description" style="margin-top: 0.5rem;">PNG, JPG, or WEBP</p>
                            <input id="image-upload" name="image" type="file" accept="image/*" class="upload-input" required>
                        </div>

                        {{-- Location Status Message --}}
                        <div id="location-status" style="text-align: center; margin-top: 1rem; font-style: italic; color: #555;"></div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full" style="padding-top: 1rem; padding-bottom: 1rem; font-size: 1.125rem; margin: 10px 0px;">
                    <i class="fa fa-paper-plane"></i> Analyze, Save & Chat
                </button>
            </form>

        </div>
    </main>

    @include('layouts.footer')

    {{-- JavaScript for file input preview and Geolocation --}}
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

                        // ** GET LOCATION AUTOMATICALLY **
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
                                        locationStatus.style.color = '#ef4444';
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
