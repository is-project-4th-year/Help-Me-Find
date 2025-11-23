<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body>
  @include('layouts.bar')

  <div class="container" style="max-width: 800px; margin-top: 2rem;">
    <div class="space-y-6">

        <div class="text-center">
            <h1 style="color: black;">My Profile</h1>
        </div>

        <div class="card" style="margin-bottom: 2rem;">
            <div class="card-header text-center">
                <div style="width: 100px; height: 100px; background: #e0e0e0; border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('images/profile.png') }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                </div>
                <h2 class="card-title">{{ $user->firstName }} {{ $user->lastName }}</h2>
                <p class="text-muted-foreground">{{ '@'.$user->username }}</p>
            </div>

            <div class="card-content space-y-4">
                <div style="border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    <label style="font-weight: bold; color: var(--primary);">Full Name</label>
                    <p style="margin: 0; font-size: 1.1em;">{{ $user->firstName }} {{ $user->lastName }}</p>
                </div>

                <div style="border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    <label style="font-weight: bold; color: var(--primary);">Email Address</label>
                    <p style="margin: 0; font-size: 1.1em;">{{ $user->email }}</p>
                </div>

                <div style="border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    <label style="font-weight: bold; color: var(--primary);">Username</label>
                    <p style="margin: 0; font-size: 1.1em;">{{ $user->username }}</p>
                </div>

                <div style="padding-bottom: 10px;">
                    <label style="font-weight: bold; color: var(--primary);">Member Since</label>
                    <p style="margin: 0; font-size: 1.1em;">{{ $user->created_at->format('F j, Y') }}</p>
                </div>

                <div style="margin-top: 2rem; text-align: center;">
                    <a href="{{ route('home') }}" class="btn btn-outline">
                        <i class="fa fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

    </div>
  </div>

  @include('layouts.footer')

</body>
</html>
