<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Report Found Item - Lost & Found</title>
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
    <h1>Upload a Found Item</h1>
    <p style="font-size: 16px; color: #5d4037; max-width: 600px; margin: 0 auto 25px;">
      Upload a photo of the found item below. The system will automatically analyze and describe it using AI recognition.
    </p>

    <!-- ===== Upload Form ===== -->
    <form method="POST" enctype="multipart/form-data">
      @csrf
      <input type="file" name="file" required>
      <br><br>
      <input type="submit" value="Upload">
    </form>

    <!-- ===== AI Result Section ===== -->
    @if(!empty($imageUrl) || !empty($description))
    <div class="result">
      <h3>Upload Successful</h3>

      @if(!empty($description))
        <h4 style="margin-top: 20px;">AI-Generated Description</h4>
        <p class="description-text" style="font-size: 16px; line-height: 1.5;">{{ $description }}</p>
      @endif

      @if(!empty($imageUrl))
        <div class="uploaded-image">
          <h4>Uploaded Image:</h4>
          <img src="{{ $imageUrl }}" alt="Uploaded Image" width="300">
        </div>
      @endif
    </div>
    @endif

    <!-- ===== Navigation Buttons ===== -->
    <div style="margin-top: 30px;">
      <a href="{{ route('lostItems') }}" class="btn">View All Found Items</a>
      <a href="{{ route('home') }}" class="btn">⬅ Back to Home</a>
    </div>
  </div>

  <!-- ===== Footer ===== -->
  <footer>
    &copy; {{ now()->year }} Help-Me-Find | Designed with ❤ by Bethelhem
  </footer>

</body>
</html>
