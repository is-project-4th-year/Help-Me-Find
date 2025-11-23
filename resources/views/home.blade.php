<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

{{-- Add jsPDF Library for PDF generation --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<body>
  @include('layouts.bar')

  <div class="container" style="max-width: 1280px; background: none; box-shadow: none; border-radius: 0; text-align: left; padding-bottom: 0;">
    <div class="space-y-6">

        <div class="text-center space-y-2" style="margin-bottom: 2.5rem;">
            <h1 style="color: black;"> Welcome Back, <span class="text-primary">{{ auth()->user()->firstName }}</span> </h1>
        </div>

        {{-- Main Grid Layout --}}
        <div class="home-grid-container">

            {{-- Column 1: QR Code --}}
            <div class="grid-col-main">
                <div class="card qr-card" style="margin-bottom: 0;">
                    <div class="card-header text-center">
                        <h2 class="card-title" style="justify-content: center; margin-bottom: 0;">
                            <i class="fa fa-qrcode"></i>
                            Your QR Code
                        </h2>
                        <p class="card-description">
                            Print and attach this to your valuable items
                        </p>
                    </div>
                    <div class="card-content space-y-4">
                        <div class="qr-image-wrapper">
                            <div class="qr-code-inner-box">
                                <p class="qr-text top-text">Did you find this lost item?</p>
                                {!! $qrCode !!}
                                <p class="qr-text bottom-text">Help me find my belonging</p>
                            </div>
                        </div>

                        {{-- Button now triggers the PDF download logic in script.js --}}
                        <button onclick="handleDownload()" class="btn btn-outline w-full" style="margin: 0;">
                            <i class="fa fa-download icon"></i>
                            Download QR Code
                        </button>

                        <div class="text-center" style="font-size: 0.875rem;">
                            <p class="text-muted-foreground">
                                When scanned, this code links to:
                                <a href="{{ $link }}" target="_blank" style="color: var(--primary); text-decoration: none; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $link }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Column 2: Other Cards --}}
            <div class="grid-col-sidebar space-y-6">

                {{-- How It Works Card --}}
                <div class="card" style="margin-bottom: 0;">
                    <div class="card-header">
                        <h3 class="card-title" style="margin-bottom: 10px;"><i class="fa fa-info-circle fa-fw"></i> How It Works</h3>
                    </div>
                    <div class="card-content space-y-4">
                        <div class="step-item">
                            <div class="step-number"><i class="fa fa-print" style="margin: 0;"></i></div>
                            <p>Print and attach your QR code to valuables.</p>
                        </div>
                        <div class="step-item">
                            <div class="step-number"><i class="fa fa-mobile" style="margin: 0;"></i></div>
                            <p>If found, someone scans the code to get your report link.</p>
                        </div>
                        <div class="step-item">
                            <div class="step-number"><i class="fa fa-comments-o" style="margin: 0;"></i></div>
                            <p>They can message you via the app to arrange a return.</p>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions Card --}}
                <div class="card" style="margin-bottom: 0;">
                    <div class="card-header">
                        <h3 class="card-title" style="margin-bottom: 10px;"><i class="fa fa-bolt fa-fw"></i> Quick Actions</h3>
                    </div>
                    <div class="card-content space-y-4">
                        <p class="text-muted-foreground" style="margin: 0 0 1rem 0;">
                            Found someone's item? Report it to help them recover their belongings.
                        </p>
                        <p class="text-muted-foreground" style="margin: 0;">
                            Lost something? Browse through found items or search for your lost item.
                        </p>
                         <div style="margin-top: 1.5rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <a href="{{ route('found') }}" class="btn" style="margin: 0;"><i class="fa fa-bullhorn"></i> Report Found Item</a>
                            <a href="{{ route('lostItems') }}" class="btn btn-secondary" style="margin: 0;"><i class="fa fa-search"></i> Browse Lost Items</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
  </div>

  @include('layouts.footer')

</body>
</html>
