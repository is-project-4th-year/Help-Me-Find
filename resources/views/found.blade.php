<!DOCTYPE html>
<html lang="en">

{{-- We replace the include with a local head to add the report.css --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Report Found Item - Help Me Find</title>

    {{-- Include existing Vite assets --}}
    @vite(['resources/css/style.css'])
    @vite(['resources/css/grid.css'])
    @vite(['resources/js/script.js'])
    @vite(['resources/js/app.js'])

    {{-- ADD THE NEW STYLESHEET --}}
    @vite(['resources/css/report.css'])

    {{-- Fallback links from original header --}}
    <link href="{{ asset('build/assets/style.css') }}" rel="stylesheet">
    <link href="{{ asset('build/assets/grid.css') }}" rel="stylesheet">
    {{-- Add new report.css fallback --}}
    <link href="{{ asset('build/assets/report.css') }}" rel="stylesheet">
    <script src="{{ asset('build/assets/script.js') }}" defer></script>

    <link rel="icon" href="{{ asset('images/bookLogo.png') }}" type="image/png">
    {{-- Use FontAwesome 6 for new icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    @include('layouts.bar')

    <main class="main-content">

        <div class="container" @if(!empty($imageUrl) || !empty($description)) style="display: flex; flex-direction: column; min-height: calc(90vh - 100px);" @endif>

            @if(!empty($imageUrl) || !empty($description))
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"> Upload Successful</h2>
                        <p class="card-description">Here is the result of your upload.</p>
                    </div>

                    <div class="card-content space-y-6">
                        @if(!empty($imageUrl))
                            <div>
                                {{-- <h3 class="form-label"><i class="fa-solid fa-image"></i> Uploaded Image</h3> --}}
                                <div class="image-preview-wrapper" style="display: block; margin-bottom: 0; border: 1px solid rgb(172, 172, 172);">
                                    <img src="{{ $imageUrl }}" alt="Uploaded Image" class="image-preview">
                                 </div>
                            </div>
                        @endif
                        @if(!empty($description))
                            <div>
                                {{-- UPDATED --}}
                                <h3 class="form-label"><i class="fa-solid fa-wand-magic-sparkles"></i> AI-Generated Description</h3>
                                <div class="ai-description-box" style="display: block;">
                                    <p>{{ $description }}</p>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <div style="margin-top: auto; padding-top: 1.5rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    {{-- Assumes your route for this page is named 'found' --}}
                    <a href="{{ route('found') }}" class="btn btn-primary">
                        <i class="fa-solid fa-plus icon"></i>
                        Report Another Found Item
                    </a>
                </div>

            @else
                <div class="page-header">
                    <h1> Report a Found Item</h1>
                    <p>Upload a photo to get an AI-generated description.</p>
                </div>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            {{-- UPDATED --}}
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
                        </div>
                    </div>

                    {{-- UPDATED --}}
                    <button type="submit" class="btn btn-primary w-full" style="padding-top: 1rem; padding-bottom: 1rem; font-size: 1.125rem; margin: 10px 0px;">
                        <i class="fa-solid fa-cloud-upload"></i> Upload and Analyze Item
                    </button>
                </form>
            @endif

        </div>
    </main>

    {{-- Simple footer, can be replaced with a layout include --}}
    <footer>
        &copy; {{ now()->year }} Help-Me-Find | Designed with ❤ by Bethelhem
    </footer>

    {{-- JavaScript for file input feedback --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('image-upload');
            if (fileInput) {
                const uploadIcon = document.querySelector('.upload-icon');
                const uploadLabel = document.querySelector('.upload-label');
                const uploadDescription = document.querySelector('.upload-zone .card-description');

                fileInput.addEventListener('change', function(e) {
                    if (e.target.files && e.target.files.length > 0) {
                        // A file was selected

                        // **** THIS IS THE FIX ****
                        const fileName = e.target.files[0].name; // Was 'e.targe'

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
                    }
                });
            }
        });
    </script>

</body>
</html>
