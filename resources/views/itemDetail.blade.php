<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body>
  @include('layouts.bar')

  <div class="container">
    <h2>Item Details</h2>

    <div class="uploaded-image" style="margin-top: 20px;">
      <img
        src="{{ asset('uploads/' . $item['ImageName']) }}"
        alt="Found Item Image"
        style="max-width: 400px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"
      >
    </div>

    <div class="result" style="margin-top: 25px;">
      <p><strong>Item Type:</strong> {{ $item['ItemType'] ?? 'Unspecified' }}</p>
      <p><strong>Date & Time Found:</strong> {{ $item['DateTime'] ?? 'Unknown' }}</p>

      @if(!empty($item['Location']))
        <p><strong>Found Location:</strong> {{ $item['Location'] }}</p>
      @endif
    </div>

    @if(!empty($item['Description']))
    <div class="result" style="margin-top: 25px;">
      <h3>AI-Generated Description</h3>
      <p style="font-size: 16px; line-height: 1.5; color: #4e342e;">
        {{ $item['Description'] }}
      </p>
    </div>
    @endif

    <div style="margin-top: 30px;">
      <a href="{{ route('lostItems') }}" class="btn btn-secondary">⬅ Back to Lost Items</a>
      <a href="{{ route('home') }}" class="btn">🏠 Home</a>
    </div>
  </div>

  <footer>
    &copy; {{ now()->year }} Help-Me-Find | Designed with ❤ by Bethelhem
  </footer>

</body>
</html>
