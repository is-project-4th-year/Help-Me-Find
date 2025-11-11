<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body>
  @include('layouts.bar')

        <main class="main-content">
            <div class="container"> {{-- Changed from main-card to container for consistency --}}

                <div class="card">
                    {{-- UPDATED --}}
                    <h2><i class="fa fa-user-circle-o fa-fw"></i> This item belongs to {{ $owner->firstName }} </h2>
                </div>

                <div class="card" style="margin-top: 20px; padding: 20px; background: #f8f5f4; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    {{-- UPDATED --}}
                    <h2><i class="fa fa-clipboard fa-fw"></i> Report Found Item Details</h2>
                    <form method="POST" action="" enctype="multipart/form-data">
                        {{-- <form method="POST" action="{{ route('finder.submit', $owner->id) }}" enctype="multipart/form-data"> --}}
                        @csrf
                        {{-- UPDATED --}}
                        <label style="display: block; text-align: left; margin-bottom: 5px;"><i class="fa fa-pencil fa-fw"></i> Description:</label>
                        <textarea name="description" required style="max-width: 500px;"></textarea>
                        {{-- UPDATED --}}
                        <label style="display: block; text-align: left; margin-bottom: 5px;"><i class="fa fa-map-marker fa-fw"></i> Location Found:</label>
                        <input name="location" required style="max-width: 500px;"><br>
                        {{-- UPDATED --}}
                        <label style="display: block; text-align: left; margin-bottom: 5px;"><i class="fa fa-camera fa-fw"></i> Upload Image (Optional):</label>
                        <input type="file" name="image" accept="image/*" style="max-width: 500px; margin-bottom: 20px;"><br>
                        {{-- UPDATED --}}
                        <button type="submit" class="btn" style="margin-top: 0;"><i class="fa fa-paper-plane"></i> Submit Report</button>
                    </form>
                </div>


            </div>
        </main>

    </div>



</body>
</html>
