<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Item Details - Lost & Found</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  @vite(['resources/css/style.css'])
</head>
<body>

  <!-- ===== Navigation Bar ===== -->
  <nav>
    <div class="logo">Help-Me-Find</div>
    <ul class="options">
      <li><a href="{{ route('home') }}">Home</a></li>
      <li><a href="{{ route('found') }}">Report Found</a></li>
      <li><a href="{{ route('lostItems') }}">Lost Items</a></li>
      <li><a href="#">About</a></li>
    </ul>
  </nav>

  <!-- ===== Main Container ===== -->
  <div class="container">
    <h2>Item Details</h2>

    <!-- Uploaded Image Section -->
    <div class="uploaded-image" style="margin-top: 20px;">
      <img
        src="{{ asset('uploads/' . $item['ImageName']) }}"
        alt="Found Item Image"
        style="max-width: 400px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"
      >
    </div>

    <!-- Item Info -->
    <div class="result" style="margin-top: 25px;">
      <p><strong>Item Type:</strong> {{ $item['ItemType'] ?? 'Unspecified' }}</p>
      <p><strong>Date & Time Found:</strong> {{ $item['DateTime'] ?? 'Unknown' }}</p>

      @if(!empty($item['Location']))
        <p><strong>Found Location:</strong> {{ $item['Location'] }}</p>
      @endif
    </div>

    <!-- AI Description -->
    @if(!empty($item['Description']))
    <div class="result" style="margin-top: 25px;">
      <h3>AI-Generated Description</h3>
      <p style="font-size: 16px; line-height: 1.5; color: #4e342e;">
        {{ $item['Description'] }}
      </p>
    </div>
    @endif

    <!-- Navigation Buttons -->
    <div style="margin-top: 30px;">
      <a href="{{ route('lostItems') }}" class="btn">⬅ Back to Lost Items</a>
      <a href="{{ route('home') }}" class="btn">🏠 Home</a>
    </div>
  </div>

  <!-- ===== Footer ===== -->
  <footer>
    &copy; {{ now()->year }} Help-Me-Find | Designed with ❤ by Bethelhem
  </footer>

</body>
</html>
