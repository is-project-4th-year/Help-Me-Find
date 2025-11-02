<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body>
  @include('layouts.bar')

        <main class="main-content">
            <div class="container"> {{-- Changed from main-card to container for consistency --}}

                <div class="card">
                    <h2> This item belongs to {{ $owner->firstName }} </h2>
                </div>

                <div class="card" style="margin-top: 20px; padding: 20px; background: #f8f5f4; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <h2>Report Found Item Details</h2>
                    <form method="POST" action="" enctype="multipart/form-data">
                        {{-- <form method="POST" action="{{ route('finder.submit', $owner->id) }}" enctype="multipart/form-data"> --}}
                        @csrf
                        <label style="display: block; text-align: left; margin-bottom: 5px;">Description:</label>
                        <textarea name="description" required style="max-width: 500px;"></textarea>
                        <label style="display: block; text-align: left; margin-bottom: 5px;">Location Found:</label>
                        <input name="location" required style="max-width: 500px;"><br>
                        <label style="display: block; text-align: left; margin-bottom: 5px;">Upload Image (Optional):</label>
                        <input type="file" name="image" accept="image/*" style="max-width: 500px; margin-bottom: 20px;"><br>
                        <button type="submit" class="btn" style="margin-top: 0;">Submit Report</button>
                    </form>
                </div>


            </div>
        </main>

    </div>



</body>
</html>
