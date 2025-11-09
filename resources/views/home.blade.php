<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body>
  @include('layouts.bar')

  <div class_alias="container" style="max-width: 1280px; background: none; box-shadow: none; border-radius: 0; text-align: left; padding-bottom: 0;">
    <div class="space-y-6">

        <div class="text-center space-y-2" style="margin-bottom: 2.5rem;">
            <h1>Welcome Back, {{ auth()->user()->firstName }}</h1>
            <p class="text-muted-foreground" style="font-size: 1.1rem; max-width: 550px; margin: 0 auto;">
                Your personal QR code for lost item recovery.
            </p>
        </div>

        {{-- NEW: Main Grid Layout --}}
        <div class="home-grid-container">

            {{-- Column 1: QR Code --}}
            <div class="grid-col-main">
                <div class="card qr-card" style="margin-bottom: 0;"> {{-- Remove card's default margin --}}
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
                                {!! $qrCode !!}
                            </div>
                        </div>

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
            <div class="grid-col-sidebar space-y-6"> {{-- space-y-6 handles gap between cards --}}

                {{-- How It Works Card --}}
                <div class="card" style="margin-bottom: 0;"> {{-- Remove card's default margin --}}
                    <div class="card-header">
                        <h3 class="card-title" style="margin-bottom: 0;">How It Works</h3>
                    </div>
                    <div class="card-content space-y-4">
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <p>Print and attach your QR code to valuables.</p>
                        </div>
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <p>If found, someone scans the code to get your report link.</p>
                        </div>
                        <div class="step-item">
                            <div class="step-number">3</div>
                            <p>They can message you via the app to arrange a return.</p>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions Card --}}
                <div class="card" style="margin-bottom: 0;"> {{-- Remove card's default margin --}}
                    <div class="card-header">
                        <h3 class="card-title" style="margin-bottom: 0;">Quick Actions</h3>
                    </div>
                    <div class="card-content space-y-4">
                        <p class="text-muted-foreground" style="margin: 0 0 1rem 0;">
                            Found someone's item? Report it to help them recover their belongings.
                        </p>
                        <p class="text-muted-foreground" style="margin: 0;">
                            Lost something? Browse through found items or search for your lost item.
                        </p>
                         <div style="margin-top: 1.5rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <a href="{{ route('found') }}" class="btn" style="margin: 0;">Report Found Item</a>
                            <a href="{{ route('lostItems') }}" class="btn btn-secondary" style="margin: 0;">Browse Lost Items</a>
                        </div>
                    </div>
                </div>
            </div>

        </div> {{-- End home-grid-container --}}

    </div>
  </div>

  <footer>
    &copy; {{ now()->year }} Help-Me-Find | Designed with ❤ by Bethelhem
  </footer>

  {{-- Script to handle SVG download (no changes) --}}
  <script>
    function handleDownload() {
        try {
            const qrCodeSvg = document.querySelector('.qr-code-inner-box svg');
            if (!qrCodeSvg) {
                console.error('QR Code SVG element not found.');
                alert('Error: Could not find QR Code to download.');
                return;
            }
            const svgData = new XMLSerializer().serializeToString(qrCodeSvg);
            const svgUrl = "data:image/svg+xml;base64," + btoa(unescape(encodeURIComponent(svgData)));
            const link = document.createElement("a");
            link.href = svgUrl;
            link.download = "help-me-find-qr-code.svg";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } catch (e) {
            console.error('Error during download:', e);
            alert('An error occurred while trying to download the QR code.');
        }
    }
  </script>

</body>
</html>
