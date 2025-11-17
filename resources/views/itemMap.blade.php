<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Location - Help Me Find</title>

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

    {{-- FontAwesome for back button --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        /* ** FIX 1: Use !important to override other styles ** */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100%;
            height: 100%;
            font-family: Arial, sans-serif;
            overflow: hidden !important; /* Prevent scrollbars */
        }

        #map {
            height: 100vh !important;
            width: 100vw !important;
            position: relative;
            z-index: 100;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background-color: white;
            color: #333;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .back-button:hover {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <a href="{{ route('itemDetail', $id) }}" class="back-button">
        <i class="fa fa-arrow-left"></i>
        Back to Item
    </a>

    <div id="map"></div>

    <script>
        window.addEventListener('load', function() {

            const item = {!! json_encode($item) !!};

            const lat = parseFloat(item.Latitude);
            const lon = parseFloat(item.Longitude);

            const map = L.map('map').setView([lat, lon], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            const marker = L.marker([lat, lon]).addTo(map);
            const itemName = item.ItemType ?? 'Found Item';
            marker.bindPopup(`<b>${itemName}</b>`).openPopup();

            setTimeout(function() {
                map.invalidateSize();
            }, 100);

        });
    </script>

</body>
</html>
