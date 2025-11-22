<!DOCTYPE html>
<html lang="en">
@include('layouts.header')
@vite(['resources/css/item.css'])

<body>
  @include('layouts.bar')

  <div class="item-detail-container">

    <a href="{{ route('lostItems') }}" class="btn btn-ghost">
      <i class="fa fa-arrow-left"></i>
      Back to Lost Items
    </a>

    @if(session('error'))
        <div class="card error-card">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid-container">

      <div class="image-section">
        <div class="card image-card">
          <img
            src="{{ asset('uploads/' . $item['ImageName']) }}"
            alt="{{ $item['ItemType'] ?? 'Found Item' }}"
          >
        </div>
      </div>

      <div class="details-section">
        <h1 class="item-title">{{ $item['ItemType'] ?? 'Item Details' }}</h1>

        @if(!empty($item['Description']))
          <p class="text-muted">{{ $item['Description'] }}</p>
        @endif

        <div class="separator"></div>

        <div class="card info-card">
          <div class="card-header">
            <h3 class="card-title">Item Information</h3>
          </div>
          <div class="card-content">
            <div class="info-block">
              <i class="fa fa-calendar fa-fw info-icon"></i>
              <div class="info-text">
                <p class="info-label">Date & Time Found:
                    <br> <b> {{ $item['DateTime'] ?? 'Unknown' }} </b>
                </p>
              </div>
            </div>

            @if(!empty($item['Location']) || (!empty($item['Latitude']) && !empty($item['Longitude'])))
              <div class="separator-small"></div>
              <div class="info-block">
                <i class="fa fa-map-marker fa-fw info-icon"></i>
                <div class="info-text">
                  <p class="info-label">Location</p>

                  @if(!empty($item['Location']))
                    <p><b>{{ $item['Location'] }}</b></p>
                  @endif

                  @if(!empty($item['Latitude']) && !empty($item['Longitude']))
                    {{-- UPDATED CLASS --}}
                    <a href="{{ route('item.map', ['id' => $id]) }}" class="btn btn-secondary btn-sm action-btn">
                        View on Map
                    </a>
                  @endif

                </div>
              </div>
            @endif

            <div class="separator-small"></div>
            <div class="info-block">
              <i class="fa fa-user fa-fw info-icon"></i>
              <div class="info-text">
                @php
                    $firstName = $item['FinderFirstName'] ?? '';
                    $lastName = $item['FinderLastName'] ?? '';
                    $finderName = trim($firstName . ' ' . $lastName);
                @endphp
                <p class="info-label">Found by: <br> <b>{{ !empty($finderName) ? $finderName : 'Anonymous Finder' }}</b></p>
                <div class="contact-buttons">
                    @if(isset($item['FinderId']) && $item['FinderId'] != auth()->id())
                        {{-- UPDATED CLASS --}}
                        <a href="{{ route('chat.with', [
                            'user' => $item['FinderId'],
                            'message' => 'Hello, this is my item that you found.',
                            'image' => 'uploads/' . $item['ImageName']
                        ]) }}" class="btn btn-secondary btn-sm action-btn">
                            Chat with Finder
                        </a>

                    @elseif(isset($item['FinderId']) && $item['FinderId'] == auth()->id())
                        <button disabled class="btn btn-secondary btn-full">
                            (You Posted This Item)
                        </button>
                    @else
                        <button disabled class="btn btn-secondary btn-full">
                            Cannot Contact Finder
                        </button>
                    @endif
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>

    {{-- ... Steps Card (No changes needed) ... --}}
    <div class="card steps-card">
        <div class="card-header">
          <h3 class="card-title">What to do next?</h3>
          <p class="text-muted card-description">Steps to safely recover this item</p>
        </div>
        <div class="card-content">
          <div class="step-block">
            <div class="step-number">1</div>
            <div class="step-text">
              <h4>Contact the finder</h4>
              <p class="text-muted">Use the messaging feature to discuss details and arrange a meetup.</p>
            </div>
          </div>
          <div class="step-block">
            <div class="step-number">2</div>
            <div class="step-text">
              <h4>Verify ownership</h4>
              <p class="text-muted">Be prepared to answer specific questions to confirm the item is yours.</p>
            </div>
          </div>
          <div class="step-block">
            <div class="step-number">3</div>
            <div class="step-text">
              <h4>Arrange a safe meetup</h4>
              <p class="text-muted">Meet in a public place during the daytime for a safe exchange.</p>
            </div>
          </div>
        </div>
      </div>

  </div>

  <footer>
    &copy; {{ now()->year }} Help-Me-Find | Designed with ❤ by Bethelhem
  </footer>

</body>
</html>
