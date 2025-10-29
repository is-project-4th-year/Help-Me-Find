<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body>
  <!-- ===== Bar ===== -->
    @include('layouts.bar')

        <!-- Main Content -->
        <main class="main-content">
            <div class="main-card">

                <div class="card">
                    <h2> This item belongs to {{ $owner->firstName }} </h2>
                </div>

                <div class="card">
                    <h2>Your Item Tag QR Code</h2>
                    <form method="POST" action="" enctype="multipart/form-data">
                        {{-- <form method="POST" action="{{ route('finder.submit', $owner->id) }}" enctype="multipart/form-data"> --}}
                        @csrf
                        <label>Description:</label><br>
                        <textarea name="description" required></textarea><br>
                        <label>Location Found:</label><br>
                        <input name="location" required><br>
                        <label>Upload Image:</label><br>
                        <input type="file" name="image" accept="image/*"><br>
                        <button type="submit">Submit Report</button>
                    </form>
                </div>


            </div>
        </main>

    </div>



</body>
</html>
